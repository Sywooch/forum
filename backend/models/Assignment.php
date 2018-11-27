<?php
namespace backend\Assignment;

use yii\db\ActiveRecord;
use common\models\User;

class Assignment extends ActiveRecord{
    public static function tableName(){
        return '{{%auth_assignment}}';
    }

    /**
     * 一条角色对应多个用户
     */

    public function getUser(){
        return $this->hasMany(User::className(),['id'=>'user_id']);
    }



}