<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use yii\filters\AccessControl;

class ActiveController extends  Controller{

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['active'],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionActive($token){
        $user=User::findOne(['auth_key'=>$token]);
        if(empty($user)){Yii::$app->session->setFlash('danger','您的激活码错误!');return $this->goHome();}
        $user->status=10;
        $res=$user->save();
        if(!$res){Yii::$app->session->setFlash('danger','激活失败!');return $this->goHome();}
        Yii::$app->session->setFlash('success','激活成功!');
        Yii::$app->user->login($user,86400);
        return $this->goHome();
    }

}