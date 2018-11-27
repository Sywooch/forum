<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Frequency extends ActiveRecord{

    public static function tableName(){
        return '{{%users_frequency}}';
    }

    public function getPostsFrequency($level){
        return static::find()->select('nums,minute')->where(['level'=>$level,'type'=>1])->asArray()->one();
    }

    public function getCommentFrequency($level){
        return static::find()->select('nums,minute')->where(['level'=>$level,'type'=>2])->asArray()->one();
    }



}