<?php
namespace backend\models;

use Yii;
use yii\base\Model;

class RoleForm extends Model{

    public $names;
    public $name;
    public $description;

    public function rules(){
        return [
            ['names','string'],
            ['name','required','message'=>'请填写角色名'],
            ['name','match','pattern'=>'/[a-zA-z]+/'],
            ['name', 'unique', 'targetClass' => Role::class, 'targetAttribute' =>'name'],
            ['description','required','message'=>'请填写角色描述'],
        ];
    }

    public function attributeLabels(){
        return [
            'name'=>'角色名',
            'description'=>'角色描述',
        ];
    }

    public function create(){
        if(!$this->validate()){return false;}
        $count=Role::find()->filterWhere(['type'=>1])->count();
        $roleModel=new Role();
        $roleModel->name=$this->name;
        $roleModel->type=1;
        $roleModel->description=$this->description;
        $roleModel->id=$count+1;
        $roleModel->created_at=time();
        $roleModel->updated_at=time();
        return $roleModel->save();
    }

    public function update(){
        if(!$this->validate()){return false;}
        $roleModel=Role::findOne(['name'=>$this->names]);
        $roleModel->name=$this->name;
        $roleModel->description=$this->description;
        $roleModel->updated_at=time();
        return $roleModel->save();
    }

    public function delete($id){
        $roleModel=Role::findOne(['id'=>$id]);
        return $roleModel->delete();
    }

}