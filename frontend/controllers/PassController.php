<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\PassForm;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use yii\filters\AccessControl;
use yii\helpers\Url;

class PassController extends Controller{


    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login','register','forget','reset','logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login','register','forget','reset'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode'=>YII_ENV_TEST?'testme':null,
                'maxLength' => 4,
                'minLength' => 4,
            ],
        ];
    }

    /**
     * 登陆
     * @return mixed
     */
    public function actionLogin(){
       $model= new LoginForm();
        if($model->load(Yii::$app->request->post())&&$model->login()){
            Yii::$app->session->setFlash('success','登陆成功!');
            return $this->goHome();
        }
        return $this->renderPartial('login',['model'=>$model]);
    }


    /**
     * 注册
     * @return mixed
     */
    public function actionRegister(){
        $model=new PassForm();
        if($model->load(Yii::$app->request->post())&&$model->register()){
            Yii::$app->session->setFlash('success','注册成功,请激活邮箱后登陆!');
            return $this->goHome();
        }
        return $this->renderPartial('reg',['model'=>$model]);
    }

    /**
     * 忘记密码
     *
     * @return mixed
     */
    public function actionForget(){
        $model = new PasswordResetRequestForm();
        if($model->load(Yii::$app->request->post())&&$model->validate()){
            if($model->sendEmail()){
                Yii::$app->session->setFlash('success', '重置链接已发送邮箱，请及时重置');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', '抱歉这个邮箱地址不能发送重置邮箱链接');
            }
        }
        return $this->render('forget', ['model' => $model]);
    }

    /**
     * 重置密码
     *
     * @return mixed
     */
    public function actionReset($token){
        try {
            $model = new ResetPasswordForm($token);
        }catch(InvalidParamException $e){
            throw new BadRequestHttpException('激活码码错误或已过期');
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', '新密码已生效');
            return $this->goHome();
        }

        return $this->render('reset',['model'=> $model,'token'=>$token]);
    }

    /**
     * 注销用户
     * @return mixed
     */
    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->redirect(Url::toRoute(['pass/login']));
    }


}