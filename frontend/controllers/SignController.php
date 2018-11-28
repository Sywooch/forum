<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Sign;
use frontend\jobs\SignJob;

class SignController extends Controller{


    /**
     * 处理签到逻辑
     * @return array
     */
    public function actionIndex(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id=Yii::$app->user->id;
        if(!$id){return ['code'=>0,'msg'=>'请登陆后签到!','data'=>''];}

        $sign=new Sign();

        $count=$sign->getTodayUserSign($id);

        if($count>0){return ['code'=>0,'msg'=>'您今日已签到！','data'=>''];}

        $res=$sign->sign($id);
        if(!$res){return ['code'=>0,'msg'=>'抱歉！签到失败请重试!','data'=>''];}

        $SignIntegral=Yii::$app->params['signIntegral'];

        Yii::$app->queue->push(new SignJob([
            'val'=>$SignIntegral,
            'uid'=>$id
        ]));
        return ['code'=>1,'msg'=>'签到成功!+'.$SignIntegral.'经验值','data'=>[]];
    }

}