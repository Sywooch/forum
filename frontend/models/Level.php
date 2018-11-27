<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Level extends ActiveRecord{

    public static function tableName(){
        return '{{%level}}';
    }

    public function getAppellation($level){
        return static::find()->select('appellation')->where(['level'=>$level])->asArray()->cache(120)->one();
    }

}

