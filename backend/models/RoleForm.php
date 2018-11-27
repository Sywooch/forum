<?php
namespace backend\models;

use Yii;
use yii\base\Model;


class RoleForm extends Model{

    public $description;
    public $name;

    public function rules(){
        return [
            // name, email, subject and body are required
            ['description','required','message'=>'请填写角色名'],
            ['description','valiteD'],
            ['name','required','message'=>'请填写角色名'],
            ['name','exist','targetAttribute'=>'name','message'=>'角色已存在'],
        ];
    }

    public function valiteD($attribute){
        if(strpos($this->description,'<script>')!==false){
            $this->addError($attribute,'角色名不得含有特殊字符');
            return false;
        }
        if(strpos($this->description,'</script>')!==false){
            $this->addError($attribute,'角色名不得含有特殊字符');
            return false;
        }
        if(strpos($this->description,'-')!==false){
            return $this->addError($attribute,'角色名不得含有特殊字符');
        }
        if(strpos($this->description,"'")!==false){
            return $this->addError($attribute,'角色名不得含有特殊字符');
        }
    }

    /**
     * 新增角色
     */

    public function addRole(){
        if(!$this->validate()){
            return false;
        }
        $auth=Yii::$app->authManager;
        $roles=$auth->createRole($this->name);
        $roles->description=$this->description;
        $res=$auth->add($roles);
        return $res;
    }

}