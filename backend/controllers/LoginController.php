<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\LoginForm;

class LoginController extends Controller{

    public function actionIndex(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new LoginForm();
            if($model->load(Yii::$app->request->post())&&$model->login()){return ['code'=>0,'info'=>'登录成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>1,'info'=>'网络错误请重试!','data'=>''];}
            foreach($errors as $v){$msg=$v[0];}
            return ['code'=>1,'info'=>$msg,'data'=>''];
        }
        return $this->renderPartial('login');
    }

    public function actionOut(){

    }

}