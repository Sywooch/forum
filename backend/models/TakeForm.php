<?php
namespace backend\models;

use yii\base\Model;

class TakeForm extends Model{

    public $role;
    public $authids;

    public function rules(){
        return [
            ['role','required','message'=>'请选择授权对象'],
            ['role','trim'],
            ['role','string'],
            ['role', 'exist', 'targetClass' => Permission::class, 'targetAttribute' =>'name'],
            ['authids','required','message'=>'请选择授予权限'],
        ];
    }

    public function attributeLabels(){
        return [
            'role'=>'授予对象',
            'authids'=>'授予权限',
        ];
    }

    public function take(){
        if(!$this->validate()){return false;}
        $permissionModel=new ItemChild();
        return $permissionModel->setTake([
            'role'=>$this->role,
            'authid'=>$this->authids
        ]);
    }
}