<?php
namespace console\controllers;

set_time_limit(0);
ini_set('memory_limit', '1024M');

use Yii;
use yii\console\Controller;

class FillingController extends Controller{

    public $quantity;

    public function options($actionID){
       return ['quantity'];
    }

    public function optionAliases()
    {
        return ['q'=>'quantity'];
    }

    public function actionIndex($quantity){
        $sql='insert into bbs_users(email,password,username)VALUES';
        for($i=0;$i<=$quantity;++$i){
            $sql.="('".Yii::$app->getSecurity()->generateRandomString()."@163.com','".Yii::$app->getSecurity()->generatePasswordHash('123456')."','".Yii::$app->getSecurity()->generateRandomString()."'),";
        }
        $sql=rtrim($sql,',');
        Yii::$app->db->createCommand($sql)->execute();
        return 0;
    }


}