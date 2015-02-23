<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users" in mysql database
 *
 * @property integer $user_id
 * @property string $date_create
 * @property string $first_name
 * @property string $last_name
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    public function getId()
    {
        return $this->user_id;
    }

    public function setId($id)
    {
        $this->user_id = $id;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    	if (\Yii::$app->request->isPost)
        	$uid = \Yii::$app->request->post('uid');
        else
        	$uid = \Yii::$app->request->post('viewer_id');

        $auth_key = md5(app_id.'_'.$uid.'_'.app_secret);

        if ($auth_key === $token) {
            return static::findOne($uid);
        }

        return null;
    }

    public static function findIdentityByUidAuthKey($uid, $auth_key, $type = null)
    {
        $true_auth_key = md5(app_id.'_'.$uid.'_'.app_secret);

        if ($auth_key === $true_auth_key) {
            if (!$user_model = static::findOne($uid))
            {
            	$user_model = new Users();
            	$first_name = \Yii::$app->request->get('first_name', null);
            	$last_name = \Yii::$app->request->get('last_name', null);
            	$user_model->user_id = $uid;
            	$user_model->first_name = $first_name;
            	$user_model->last_name = $last_name;
            	$user_model->save();
                $user_model = Users::findOne($uid); // TODO: просто сделать load default values
            }
            return $user_model;
        }

        return null;
    }
}
