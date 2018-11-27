<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Reset extends ActiveRecord{

    public static function tableName()
    {
        return '{{%user_reset_log}}';
    }


}