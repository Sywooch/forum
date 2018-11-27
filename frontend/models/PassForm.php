<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\helpers\Url;
use frontend\jobs\SendEmailJob;


class PassForm extends Model{

    public $email;
    public $password;
    public $repassword;
    public $verifyCode;
    private $user;

    public function rules(){
        return [
            ['email','required','message'=>'请填写邮箱'],
            ['email','trim'],
            ['email','string','max'=>35],
            ['password','required','message'=>'请填写密码'],
            ['password','trim'],
            ['repassword','required','message'=>'请填写确定密码'],
            ['repassword','trim'],
            ['verifyCode','required','message'=>'请填写验证码'],
            ['verifyCode','trim'],
            ['verifyCode','string','min'=>4,'message'=>'验证码必须为4位'],
            ['email','email','message'=>'邮箱格式错误'],
            ['email','validateEmail'],
            //['email','unique','targetClass'=>'\common\models\User','targetAttribute'=>'email','message'=>'邮箱已被注册!'],
            ['password', 'string', 'length' =>[6,12],'message'=>'密码长度须为6-12位'],
            ['password','match','pattern' =>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9a-zA-Z]+$/','message'=>'密码须为字母和数字组合'],
            ['repassword', 'string', 'length' => [6, 12],'message'=>'确认密码长度须为6-12位'],
            ['repassword','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9a-zA-Z]+$/','message'=>'确认密码须为字母和数字组合'],
            ['password','compare','compareAttribute'=>'repassword','message'=>'密码和确认密码不一致'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels(){
        return [
            'email' => '邮箱',
            'password' => '密码',
            'repassword' => '确认密码',
            'verifyCode' => '验证码',
        ];
    }

    public function validateEmail($attribute){
        if($this->hasErrors()){return false;}
        $redis=Yii::$app->redis;
        $res=$redis->sismember('register_user',$this->email);
        if($res){$this->addError($attribute, '邮箱已被注册！');return false;}
    }

    /**
     * 用户注册
     * @return mixed
     */
    public function register(){
        if(!$this->validate()){return false;}
        $user=new User();
        $user->email=$this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->ip=Yii::$app->request->userIP;
        $user->avatar=Yii::$app->params['default_avatar'];
        $user->created_at=time();
        $res=$user->save();
        if(!$res){$this->addError('email','注册失败!');return false;}
        $UserPostModel=new UserPost();
        $UserPostModel->user_id=$user->id;
        $UserPostModel->post_num=0;
        $UserPostModel->save();
        $redis=Yii::$app->redis;
        $redis->sadd('register_user',$this->email);
        $this->user=$user;
        $this->email();
        return true;
    }

    /**
     * 队列发送激活邮件
     * @return mixed
     */
    private function email(){
        Yii::$app->queue->push(new SendEmailJob([
            'type'=>'register',
            'title'=>'请激活您的邮箱',
            'to'=>$this->email,
            'url'=>Url::toRoute(['/active/active','token'=>$this->user->auth_key],true),
        ]));
    }

}