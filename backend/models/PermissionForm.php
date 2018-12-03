<?php
namespace backend\models;

use Yii;
use yii\base\Model;

class PermissionForm extends Model{

    public $id;
    public $fid;
    public $name;
    public $description;

    public function rules(){
        return [
            ['id','number','min'=>0],
            ['fid','number','min'=>0],
            ['name','required','message'=>'请填写权限名'],
            ['name','required','message'=>'请填写权限名'],
            ['name','match','pattern'=>'/[a-zA-z]+(\/){1}/','message'=>'权限格式错误'],
            ['description','required','message'=>'请填写权限描述'],
            ['description','match','pattern'=>'/^[\u4e00-\u9fa5]+(-)?[\u4e00-\u9fa5]+$/','message'=>'权限描述必须为中文'],
        ];
    }

    public function attributeLabels(){
        return [
            'fid'=>'上级权限',
            'name'=>'权限链接',
            'description'=>'权限描述',
        ];
    }

    public function scenarios(){
        return [
            'create'=>['fid','name','description'],
            'update'=>['id','fid','name','description'],
        ];
    }

    public function create(){
        $count=Permission::find()->where(['type'=>2])->count();
        $permissionModel=new Permission();
        $permissionModel->type=2;
        $permissionModel->id=$count+1;
        $permissionModel->fid=$this->fid;
        $permissionModel->name=$this->name;
        $permissionModel->description=$this->description;
        $permissionModel->created_at=time();
        $permissionModel->updated_at=time();
        return $permissionModel->save();
    }

    public function update(){
        $permissionModel=Permission::findOne(['id'=>$this->id]);
        $permissionModel->fid=$this->fid;
        $permissionModel->name=$this->name;
        $permissionModel->description=$this->description;
        $permissionModel->updated_at=time();
        return $permissionModel->save();
    }

    public function delete($id){
       return Permission::deleteAll(['or',['=','id',$id],['=','fid',$id]]);
    }

}