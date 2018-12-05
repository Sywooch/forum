<?php
namespace backend\models;

use yii\base\Model;

class AccountForm extends Model{

    public $id;
    public $role;
    public $username;
    public $password;

    public function rules(){
        return [
            ['id','number'],
            ['id','exist','targetClass' =>User::class,'targetAttribute' =>'id'],
            ['username','required','message'=>'请填写用户名'],
            ['username','string','length'=>[1,30]],
            ['username','match','pattern'=>'/[a-zA-z]+/'],
            ['role','required','message'=>'请选择用户身份'],
            ['role','exist','targetClass' =>Role::class,'targetAttribute' =>'name'],
            ['username', 'unique', 'targetClass' =>User::class,'targetAttribute' =>'username'],
            ['password','required','message'=>'请填写密码'],
            ['password','string','length'=>[0,20]],
        ];
    }

    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'role'=>'用户身份',
        ];
    }

    public function scenarios(){
        return [
            'create'=>['username','password','role'],
            'update'=>['id','password','role'],
            'delete'=>['id'],
        ];
    }

    public function create(){
        if(!$this->validate()){return false;}
        $userModel=new User();
        $userModel->username=$this->username;
        $userModel->setPassword($this->password);
        $userModel->created_at=time();
        $result=$userModel->save();
        if(!$result){$this->addError('username','添加失败!');return false;}
        $assignmentModel=new Assignment();
        $assignmentModel->item_name=$this->role;
        $assignmentModel->user_id=$userModel->id;
        $assignmentModel->created_at=time();
        $assignmentModel->save();
        return true;
    }

    public function update(){
        if(!$this->validate()){return false;}
        $userModel=User::findOne($this->id);
        $userModel->setPassword($this->password);
        $userModel->updated_at=time();
        $result=$userModel->save();
        if(!$result){$this->addError('username','修改失败!');return false;}
        $assignmentModel=Assignment::findOne(['user_id'=>$this->id]);
        $assignmentModel->item_name=$this->role;
        $assignmentModel->save();
        return true;
    }

    public function delete(){
        $user=User::findOne(['id'=>$this->id]);
        ItemChild::deleteAll(['parent'=>$user->username]);
        return $user->delete();
    }

}