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
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }
}
