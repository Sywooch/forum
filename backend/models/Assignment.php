<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Assignment extends ActiveRecord{
    public static function tableName(){
        return '{{%auth_assignment}}';
    }

    public function getUser(){
        return $this->hasMany(User::className(), ['id' => 'user_id'])->select('id,username');
    }

    public function getItem(){
        return $this->hasOne(Permission::className(),['name'=>'item_name'])->select('name,description');
    }

}