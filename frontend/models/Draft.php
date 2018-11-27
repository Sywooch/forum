<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use common\models\User;
use frontend\models\Plate;
use frontend\models\Comment;

class Draft extends ActiveRecord{

    public static function tableName(){
        return '{{%draft}}';
    }

    /**
     * 草稿和用户一对一
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id'])->select('id,email,username,avatar,intro,integral,experience');
    }

    /**
     * 草稿和板块一对一
     */
    public function getPlate(){
        return $this->hasOne(Plate::className(),['id'=>'plate_id'])->select('id,name');
    }
}