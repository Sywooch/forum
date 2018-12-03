<?php
namespace backend\models;

use yii\base\Model;

class MenuForm extends Model{

    public $id;
    public $pid;
    public $menu_name;
    public $menu_url;

    public function rules(){
        return [
            ['id','required','message'=>'请选择编辑数据'],
            ['id','number','min'=>1],
            ['pid','number','min'=>0],
            ['menu_name','required','message'=>'请填写菜单名称'],
            ['menu_name','string','max'=>10],
            ['menu_name','match','pattern'=>'/^[\u4e00-\u9fa5]+(-)?[\u4e00-\u9fa5]+$/','message'=>'菜单名称必须为中文'],
            ['menu_url','required','message'=>'请填写菜单链接'],
            ['menu_url','string','max'=>20],
            ['menu_url','match','pattern'=>'/[a-zA-z]+(\/){1}/','message'=>'菜单链接格式错误'],
        ];
    }

    public function attributeLabels(){
        return [
            'pid'=>'菜单级别',
            'menu_name'=>'菜单名称',
            'menu_url'=>'菜单链接',
        ];
    }

    public function scenarios(){
        return [
            'create'=>['pid','menu_name','menu_url'],
            'update'=>['id','pid','menu_name','menu_url'],
        ];
    }

    public function create(){
        $menuModel=new Menu();
        $menuModel->menu_name=$this->menu_name;
        $menuModel->menu_url=$this->menu_url;
        $menuModel->pid=$this->pid;
        $menuModel->created_at=time();
        $menuModel->updated_at=time();
        return $menuModel->save();
    }

    public function update(){
        $menuModel=Menu::findOne($this->id);
        $menuModel->menu_name=$this->menu_name;
        $menuModel->menu_url=$this->menu_url;
        $menuModel->pid=$this->pid;
        $menuModel->updated_at=time();
        return $menuModel->save();
    }

}