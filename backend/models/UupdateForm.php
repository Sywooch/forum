<?php
namespace backend\models;

use yii\base\Model;
use common\models\User;

class UupdateForm extends Model{

    public $username;
    public $groups;
    public $status;
    public $id;

    public function rules(){
        return [
            ['username','required','message'=>'请填写用户名'],
            ['username','trim'],
            ['username','string','max'=>10,'message'=>'用户名最大长度为10个字符'],
            ['username','valiteName'],
            ['groups','required','message'=>'请选择组别'],
            ['groups','in','range'=>[1,2,10],'message'=>'用户组别不存在'],
            ['status','required','message'=>'请选择状态'],
            ['status','in','range'=>[1,2,3,10],'message'=>'状态无效'],
            ['id','required','message'=>'请选择修改数据'],
            ['id', 'integer','message'=>'数据不存在'],
        ];
    }

    public function valiteName($attribute){
        if(strpos($this->username,'<script>')!==false){
            $this->addError($attribute,'用户名不得含有特殊字符');
            return false;
        }
        if(strpos($this->username,'</script>')!==false){
            $this->addError($attribute,'用户名不得含有特殊字符');
            return false;
        }
        if(strpos($this->username,'-')!==false){
            return $this->addError($attribute,'用户名不得含有特殊字符');
        }
    }

    public function updates(){
        if(!$this->validate()){
            return false;
        }
        $user=User::findOne($this->id);
        $user->username=$this->username;
        $user->groups=$this->groups;
        $user->status=$this->status;
        $res=$user->save();
        return $res?true:false;
    }
}