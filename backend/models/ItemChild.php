<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class ItemChild extends ActiveRecord{

    public static function tableName(){
        return '{{%auth_item_child}}';
    }

    public function setTake($params){
        static::deleteAll(['parent'=>$params['role']]);
        $data=[];
        foreach($params['authid'] as $v){
            $data[]=[$params['role'],$v];
        }
        if(empty($data)){return false;}
        return Yii::$app->db->createCommand()->batchInsert('{{%auth_item_child}}',['parent','child'],$data)->execute();
    }

}