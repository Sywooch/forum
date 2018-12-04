<?php
namespace backend\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model{

    public $username;
    public $password;
    public $remember;

    private $_user;

    public function rules(){
        return [
            ['username','required','message'=>'请填写用户名'],
            ['username','trim'],
            ['username','string','length'=>[1,20]],
            ['username', 'match', 'pattern' => '/[0-9a-zA-Z]+/'],
            ['password','required','message'=>'请填写密码'],
            ['password','trim'],
            ['password','string','length'=>[1,20]],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels(){
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }

    public function validatePassword($attribute){
        if(!$this->hasErrors()){
            $user = $this->getUser();
            if(!$user){$this->addError('username', '无此用户');return false;}
            if(!$user->validatePassword($this->password)){
                $this->addError($attribute, '用户名或密码错误');
                return false;
            }
        }
    }

    protected function getUser(){
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }

    public function login(){
        if($this->validate()){
            $initRes=$this->inits($this->_user->id);
            if($initRes===false){return false;}
            return Yii::$app->user->login($this->getUser(),$this->remember?3600 * 24 * 30:3600 * 24);
        }
        return false;
    }

    public function inits($id){
        $loginRole=Assignment::find()->select('item_name')->where(['user_id'=>$id])->column();
        if(empty($loginRole)){$this->addError('username', '当前用户无权限');return false;}
        $PermissionList=ItemChild::find()->select('child')->where(['parent'=>$loginRole])->column();
        if(empty($PermissionList)){$this->addError('username', '当前用户无权限');return false;}
        $UserMenu=Menu::find()->select('id,menu_name,menu_url,pid')->where(['in','menu_url',$PermissionList])->asArray()->all();
        if(empty($UserMenu)){$this->addError('username', '当前用户无权限');return false;}
        $lists=[];
        foreach($UserMenu as $k=>$v){
            if($v['pid']==0){
                $lists[$k]=$v;
                foreach($UserMenu as $ks=>$vs){
                    if($vs['pid']==$v['id']){
                        $lists[$k]['child'][]=$vs;
                        $lists[$k]['child_url'][]=$vs['menu_url'];
                    }
                }
            }
        }
        $session=Yii::$app->session;
        $session->set('user_menu',$lists);
        return true;
    }

}