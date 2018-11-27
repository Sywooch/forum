<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use common\models\User;

class Prive extends ActiveRecord{

    public static function tableName(){
        return '{{%priv_msg}}';
    }

    /**
     * 私信和用户一对一
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'users_id']);
    }

    /**
     * 私信和用户一对一
     */
    public function getUsers(){
        return $this->hasOne(User::className(),['id'=>'user_id'])->select('id,username');
    }


}