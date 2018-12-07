<?php
namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\Plate;

class PlateController extends Controller{

    public function actionIndex(){
        $plates=Plate::find()->select('id,fid,name')->where(['!=','fid',0])->cache(300)->asArray()->all();
        return $this->render('index',['plates'=>$plates]);
    }

}