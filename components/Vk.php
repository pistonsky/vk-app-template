<?php
/*
 * Class Vk
 * author: Dmitriy Nyashkin, Alexander Tsygankov
 */
namespace app\components;

use Yii;

class Vk{

    private static $vk = null;

    const CALLBACK_BLANK = 'https://oauth.vk.com/blank.html';
    const AUTHORIZE_URL = 'https://oauth.vk.com/authorize?client_id={client_id}&scope={scope}&redirect_uri={redirect_uri}&display={display}&v=5.15&response_type={response_type}';
    const GET_TOKEN_URL = 'https://oauth.vk.com/access_token?client_id={client_id}&client_secret={client_secret}&code={code}&redirect_uri={redirect_uri}';
    const GET_ACCESS_TOKEN_URL = 'https://oauth.vk.com/access_token?client_id={client_id}&client_secret={client_secret}&v=5.23&grant_type=client_credentials';
    const METHOD_URL = 'https://api.vk.com/method/';

    /**
     * Logger settings
     */
    public static $MAX_LOG_SIZE = 10000;
    public static $LOG_PATH = '/tmp/Vk.log';

    public $secret_key = null;
    public $scope = array();
    public $client_id = null;
    public $access_token = null;
    public $client_secret = null;
    public $owner_id = 0;

    /**
     * Это Конструктор (Кэп.)
     * Передаются параметры настроек
     * @param array $options
     */
    private function __construct($options = array()){

        $this->scope[]='offline';

        $this->client_id = Yii::$app->params['app_id'];
        $this->secret_key = Yii::$app->params['app_secret'];

        if(count($options) > 0){
            foreach($options as $key => $value){
                if($key == 'scope' && is_string($value)){
                    $_scope = explode(',', $value);
                    $this->scope = array_merge($this->scope, $_scope);
                } else {
                    $this->$key = $value;
                }

            }
        }
    }

    /**
     * Выполнение вызова Api метода
     * @param string $method - метод, http://vk.com/dev/methods
     * @param array $vars - параметры метода
     * @return array - выводит массив данных или ошибку (но тоже в массиве)
     */
    private function _api($method = '', $vars = array(), $secure = true){

        $params = http_build_query($vars);

        $url = $this->http_build_query($method, $params, $secure);

        return (array)$this->call($url);
    }

    public static function api($method = '', $vars = array(), $secure = true){
        if (empty(self::$vk)) {
            self::$vk = new Vk();
        }
        return self::$vk->_api($method, $vars, $secure);
    }


    /**
     * Построение конечного URI для выхова
     * @param $method
     * @param string $params
     * @return string
     */
    private function http_build_query($method, $params = '', $secure = true){
        if ($secure) {
            return  self::METHOD_URL . $method . '?' . $params.'&access_token=' . $this->access_token;
        } else {
            return  self::METHOD_URL . $method . '?' . $params;
        }
    }

    /**
     * Получить ссылка на запрос прав доступа
     *
     * @param string $type тип ответа (code - одноразовый код авторизации , token - готовый access token)
     * @return mixed
     */
    private function _get_code_token($type="code"){

        $url = self::AUTHORIZE_URL;

        $scope = implode(',', $this->scope);

        $url = str_replace('{client_id}', $this->client_id, $url);
        $url = str_replace('{scope}', $scope, $url);
        $url = str_replace('{redirect_uri}', self::CALLBACK_BLANK, $url);
        $url = str_replace('{display}', 'page', $url);
        $url = str_replace('{response_type}', $type, $url);

        return $url;

    }

    public static function get_code_token($type="code"){
        if (empty(self::$vk)) {
            self::$vk = new Vk();
        }
        return self::$vk->_get_code_token($type);
    }

    private function _get_token($code){

        $url = self::GET_TOKEN_URL;
        $url = str_replace('{code}', $code, $url);
        $url = str_replace('{client_id}', $this->client_id, $url);
        $url = str_replace('{client_secret}', $this->secret_key, $url);
        $url = str_replace('{redirect_uri}', self::CALLBACK_BLANK, $url);

        return $this->call($url);
    }

    public static function get_token($code){
        if (empty(self::$vk)) {
            self::$vk = new Vk();
        }
        return self::$vk->_get_token($code);
    }

    private function _get_access_token(){

        $url = self::GET_ACCESS_TOKEN_URL;
        $url = str_replace('{client_id}', $this->client_id, $url);
        $url = str_replace('{client_secret}', $this->secret_key, $url);
        $result = $this->call($url);
        return $this->access_token = $result['access_token'];
    }

    public static function get_access_token(){
        if (empty(self::$vk)) {
            self::$vk = new Vk();
        }
        return self::$vk->_get_access_token();
    }

    function call($url = ''){

        if(function_exists('curl_init')) $json = $this->curl_post($url); else $json = file_get_contents($url);

        self::log($json);

        $json = json_decode($json, true);

        if(isset($json['response'])) return $json['response'];

        return $json;
    }

    // @deprecated
    private function curl_get($url){
        if(!function_exists('curl_init')) return false;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tmp = curl_exec ($ch);
        curl_close ($ch);
        $tmp = preg_replace('/(?s)<meta http-equiv="Expires"[^>]*>/i', '', $tmp);
        return $tmp;
    }

    private function curl_post($url){

        if(!function_exists('curl_init')) return false;

        $param = parse_url($url);

        if( $curl = curl_init() ) {

            curl_setopt($curl, CURLOPT_URL, $param['scheme'].'://'.$param['host'].$param['path']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param['query']);
            $out = curl_exec($curl);

            curl_close($curl);

            return $out;
        }

        return false;
    }
    /**
     * @param array $options
     */
    private function _set_options($options = array()){

        if(count($options) > 0){
            foreach($options as $key => $value){
                if($key == 'scope' && is_string($value)){
                    $_scope = explode(',', $value);
                    $this->scope = array_merge($this->scope, $_scope);
                } else {
                    $this->$key = $value;
                }

            }
        }

    }

    public static function set_options($options = array()){
        if (empty(self::$vk)) {
            self::$vk = new Vk();
        }
        return self::$vk->_set_options($options);
    }

    /**
     * @param bool $gid
     * @param array $files
     * @return array|bool
     */
    private function _upload_photo($gid = false, $files = array()){

        if(count($files) == 0) return false;
        if(!function_exists('curl_init')) return false;

        $data_json = $this->_api('photos.getWallUploadServer', array('gid'=> intval($gid)));

        if(!isset($data_json['upload_url'])) return false;

        $temp = array_chunk($files, 4);

        $files = array();
        $attachments = array();

        foreach ($temp[0] as $key => $data) {
            $path = realpath($data);

            if($path){
              $files['file' . ($key+1)] = '@' . realpath($data);
            }
        }

        $upload_url = $data_json['upload_url'];

        $ch = curl_init($upload_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $files);

        $upload_data = json_decode(curl_exec($ch), true);

        $response = $this->_api('photos.saveWallPhoto', $upload_data);

        if(count($response) > 0){

            foreach($response as $photo){

                $attachments[] = $photo['id'];
            }
        }

        return $attachments;

    }

    public static function upload_photo($gid = false, $files = array()){
        if (empty(self::$vk)) {
            self::$vk = new Vk();
        }
        return self::$vk->_upload_photo($gid, $files);
    }

    /**
     * Заливка документа (например GIF файл)
     *
     * @param bool $gid
     * @param $file
     * @return bool|string
     */
    private function _upload_doc($gid = false, $file){

        if(!is_string($file)) return false;
        if(!function_exists('curl_init')) return false;

        $data_json = $this->_api('docs.getUploadServer', array('gid'=> intval($gid)));

        var_dump($data_json);

        if(!isset($data_json['upload_url'])) return false;

        $attachment = false;

        $path = realpath($file);

        if(!$path) return false;

        $files['file'] = '@' . $file;

        $upload_url = $data_json['upload_url'];

        $ch = curl_init($upload_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $files);

        $upload_data = json_decode(curl_exec($ch), true);

        $response = $this->_api('docs.save', $upload_data);

        if(count($response) > 0){

            foreach($response as $photo){

                $attachment = 'doc'.$photo['owner_id'].'_'.$photo['did'];
            }
        }

        return $attachment;

    }

    public static function upload_doc($gid = false, $file){
        if (empty(self::$vk)) {
            self::$vk = new Vk();
        }
        return self::$vk->_upload_doc($gid, $files);
    }

    /**
     *
     * Заливка видео
     *
     * http://vk.com/dev/video.save
     *
     * @param array $options
     * @param bool $file
     * @return bool|string
     */
    private function _upload_video($options = array(), $file = false){

        if(!is_array($options)) return false;
        if(!function_exists('curl_init')) return false;

        $data_json = $this->_api('video.save', $options);

        if(!isset($data_json['upload_url'])) return false;

        $attachment = 'video'.$data_json['owner_id'].'_'.$data_json['vid'];

        $upload_url = $data_json['upload_url'];
        $ch = curl_init($upload_url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");

        // если указан файл то заливаем его отправкой POST переменной video_file
        if($file && file_exists($file)){
            //@todo надо протестировать заливку
            $path = realpath($file);

            if(!$path) return false;

            $files['video_file'] = '@' . $file;

            curl_setopt($ch, CURLOPT_POSTFIELDS, $files);
            curl_exec($ch);

        // иначе просто обращаемся по адресу (ну надо так!)
        } else {

            curl_exec($ch);
        }

        return $attachment;

    }

    public static function upload_video($options = array(), $file = false){
        if (empty(self::$vk)) {
            self::$vk = new Vk();
        }
        return self::$vk->_upload_video($options, $file);
    }

    /**
     * Logger
     */
    private static function log($msg)
    {
        $bt = debug_backtrace(); array_shift($bt); $callee = array_shift($bt);

        if (!isset($callee['file']))
            $callee = array_shift($bt);

        $msg = basename($callee['file'], '.php').":".$callee['line']." - ".@date('Y-m-d H:i:s')." - ".$msg;

        $size = strlen($msg);
        if($size > self::$MAX_LOG_SIZE) $msg = substr($msg, 0, self::$MAX_LOG_SIZE) . ' ...';

        error_log($msg . PHP_EOL, 3, self::$LOG_PATH);
    }
}