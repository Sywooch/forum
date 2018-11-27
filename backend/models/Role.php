<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Role extends ActiveRecord{

    public static function tableName(){
        return '{{%auth_item}}';
    }




}