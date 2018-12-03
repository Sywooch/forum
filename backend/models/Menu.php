<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Menu extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_menu}}';
    }





}