<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\LoginForm;

class LoginController extends Controller{

    public function actionLogin(){
        $model=new LoginForm();
        if($model->load(Yii::$app->request->post())&&$model->login()){
            Yii::$app->session->setFlash('success','欢迎回来!');
            return $this->redirect('index.php?r=Home/index');
        }
        return $this->renderPartial('login',['model'=>$model]);
    }

}