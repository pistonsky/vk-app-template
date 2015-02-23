<?php

namespace app\components;

use app\models\Admins;

class User extends \yii\web\User
{
	public function loginByAuthKey($uid, $auth_key, $type)
	{
		/* @var $class IdentityInterface */
        $class = $this->identityClass;
        $identity = $class::findIdentityByUidAuthKey($uid, $auth_key, $type);
        if ($identity && $this->login($identity)) {
            return $identity;
        } else {
            return null;
        }
	}

    public function adminLoginByAuthKey($auth_key)
    {
        return Admins::findOne(['auth_key' => $auth_key]);
    }
}