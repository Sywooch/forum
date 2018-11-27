<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use frontend\models\Plate;
use common\models\User;

class Moder extends ActiveRecord{

    public static function tableName(){
        return '{{%moderator}}';
    }

    /**
     * 版主和板块多对多
     */
    public function getPlates(){
        return $this->hasMany(Plate::className(),['id'=>'plate_id']);
    }

    /**
     * 版主和用户一对一
     */
    public function getUser(){
        return $this->hasMany(User::className(),['id'=>'user_id']);
    }



}