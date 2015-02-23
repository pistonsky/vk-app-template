<?php

namespace app\controllers;

use app\models\Users;
use app\models\LogRequests;
use app\models\LogSession;
use app\filters\VkAuth;

class Controller extends \yii\rest\Controller
{
	protected $uid;
	private $auth_key;
	public $layout = "@app/views/layouts/iframe";

	public function behaviors()
	{
		return [
			'authenticator' => [
				'class' => VkAuth::className(),
			],
			'corsFilter' => [
				'class' => \yii\filters\Cors::className(),
			],
			'rateLimiter' => [
				'class' => \yii\filters\RateLimiter::className(),
			],
		];
	}

	private function udate($format, $utimestamp = null)
	{
		if (is_null($utimestamp))
			$utimestamp = microtime(true);
	 
		$timestamp = floor($utimestamp);
		$milliseconds = round(($utimestamp - $timestamp) * 1000000);
	 
		return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
	}

	/**
	 * Return data to browser as JSON and end application.
	 * @param array $data
	 */
	protected function renderJSON($data)
	{
		header('Content-type: application/json');

		echo json_encode(array_merge($data,['time'=>\Yii::getLogger()->getElapsedTime()*1000]));

		// логирование
			\Yii::info("\n" . json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), 'api');

		// замер загрузки процессора
			// $result = exec('mpstat');
			// $cpu = '0';
			// if (preg_match('/.*all\s+(\d+\.\d\d)/', $result, $matches))
			// 	$cpu = $matches[1];

		// останавливаем замер времени запроса
			\Yii::info(json_encode([
					'timestamp' => $this->udate('Y-m-d\Th:i:s.u\Z'),
					// 'cpu' => $cpu,
					'total_time_ms' => \Yii::getLogger()->getElapsedTime()*1000,
					'total_db_queries_count' => \Yii::getLogger()->getDbProfiling()[0],
					'total_db_queries_time_ms' => \Yii::getLogger()->getDbProfiling()[1]*1000,
				]), \Yii::$app->request->url);
			\Yii::endProfile("apiTotalTimeBenchmark \n\tTotal time: " . \Yii::getLogger()->getElapsedTime() . "\n\tAll profiling results:\n" . json_encode(\Yii::getLogger()->getProfiling(), JSON_PRETTY_PRINT), \Yii::$app->request->url);

		exit; // по-чёрному, это пиздец
	}

	public function beforeAction($action)
	{
		parent::beforeAction($action);
		
		// начинаем замер времени запроса
			\Yii::beginProfile('apiTotalTimeBenchmark');

		// чтобы замерить сколько у нас запросов в минуту
			$log = new LogRequests();
			$log->timestamp = time();
			$log->save();

		// проверка входных параметров
			if (!isset($_POST['uid']) || (($this->uid = $_POST['uid']) == ''))
			{
				echo json_encode([
					'error' => [
						'code' => InsufficientInputParameters,
						'msg' => 'uid is not set'
					]
				]);
				return false;
			}

			if (!isset($_POST['auth_key']) || (($this->auth_key = $_POST['auth_key']) == ''))
			{
				echo json_encode([
					'error' => [
						'code' => InsufficientInputParameters,
						'msg' => 'auth_key is not set'
					]
				]);
				return false;
			}

		// $auth_key = md5(app_id.'_'.$this->uid.'_'.app_secret);

		// if ($auth_key != $this->auth_key)
		// {
		// 	$this->error(WrongAuthKey, "auth_key is wrong for uid " . $this->uid);
		// 	return false;
		// }

		// лог сессий
            if (!empty(\Yii::$app->user->sid)) {
                LogSession::log(\Yii::$app->user->sid);
            }


		return true;
	}

	protected function checkInputParameters($names)
	{
		$vars = [];
		foreach ($names as $name)
		{
			if (!isset($_POST[$name]) || ((${$name} = $_POST[$name]) == ''))
			{
				$this->error(InsufficientInputParameters, $name . ' is not set');
			}
			$vars[] = ${$name};
		}
		return $vars;
	}

	protected function getUser()
	{
		if (!$user_model = \Yii::$app->user->identity /*Users::findOne(['user_id'=>$this->uid])*/)
		{
			$this->error(UserNotFound, 'user with ID ' . $this->uid . ' is not found in users table');
		}
		return $user_model;
	}

	protected function getUserItemsIndexed($user_model)
	{
		$user_items_indexed = [];
		$user_items = json_decode($user_model->items);
		if (is_array($user_items))
		{
			foreach ($user_items as $item)
			{
				$user_items_indexed[$item->id] = $item;
			}
		}
		return $user_items_indexed;
	}

	protected function error($code, $msg)
	{
		$this->renderJSON([
				'error' => [
					'code' => $code,
					'msg' => $msg
				]
			]);
	}
}