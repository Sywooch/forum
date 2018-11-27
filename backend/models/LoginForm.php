<?php
namespace backend\models;

use yii\base\Model;
use common\models\User;

class LoginForm extends Model{

    public $email;
    public $password;

    private $_user;

    public function rules(){
        return [
            ['email','required','message'=>'请填写邮箱'],
            ['email','trim'],
            ['password','required','message'=>'请填写密码'],
            ['password','trim'],
            ['email','email','message'=>'邮箱格式错误'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels(){
        return [
            'email' => '邮箱',
            'password' => '密码',
        ];
    }

    /**
     * 验证用户密码
     *
     * @param string $attribute
     */
    public function validatePassword($attribute){
        if(!$this->hasErrors()){
            $user = $this->getUser();
            if(!$user){
                $this->addError('email', '无此用户');
            }
            if(!$user->validatePassword($this->password)){
                $this->addError($attribute, '用户名或密码错误');
            }
        }
    }

    /**
     * 通过用户邮箱查找用户
     *
     * @return User|null
     */
    protected function getUser(){
        if ($this->_user === null) {
            $this->_user = User::findByEmails($this->email);
        }
        return $this->_user;
    }

    /**
     * 用户登陆.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(){
        if($this->validate()){
            return \Yii::$app->user->login($this->getUser(),3600 * 24 * 30);
        }
        return false;
    }

}