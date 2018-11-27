<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

class LoginForm extends Model{
    public $email;
    public $password;
    public $rememberMe = false;

    private $_user;

    public function rules(){
        return [
            ['email','required','message'=>'请填写邮箱'],
            ['email','trim'],
            ['email','string','max'=>35],
            ['password','required','message'=>'请填写密码'],
            ['password','trim'],
            ['password', 'string', 'length' =>[6,12]],
            ['email','email','message'=>'邮箱格式错误'],
            ['email','validateEmail'],
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function validateEmail($attribute){
        if($this->hasErrors()){return false;}
        $redis=Yii::$app->redis;
        $res=$redis->sismember('register_user',$this->email);
        if(!$res){$this->addError($attribute,'无此账户！');return false;}
    }

    public function validatePassword($attribute){
        if($this->hasErrors()){return false;}
        $user = $this->getUser();
        if(User::STATUS_ACTIVE!==$user->status){
            $StatusList=Yii::$app->params['userStatusMessage'];
            $this->addError('email',$StatusList[$user->status]);
            return false;
        }
        if (!$user->validatePassword($this->password)){$this->addError($attribute, '用户名或密码错误');return false;}
    }

    public function attributeLabels(){
        return [
            'email' => '邮箱',
            'password' => '密码',
            'rememberMe'=>'记住我',
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(){
        if(!$this->validate()){return false;}
        $user=$this->getUser();
        $user->update_at=time();
        $user->save();
        return Yii::$app->user->login($user,$this->rememberMe?604800:86400);
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser(){
        if($this->_user === null){
            $this->_user = User::findByEmail($this->email);
        }
        return $this->_user;
    }


}