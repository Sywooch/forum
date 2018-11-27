<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use common\models\User;

class Site extends ActiveRecord{

    public static function tableName(){
        return '{{%site_msg}}';
    }

    /**
     * 站内信和用户一对一
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }


}