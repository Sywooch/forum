<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

class UpdateForm extends Model{
    public $username;
    public $intro;
    public $city;
    public $sex;
    public $old;
    public $new;
    public $news;

    public function rules(){
        return [
            ['username','required','message'=>'请填写用户名'],
            ['username','trim'],
            ['username','string','max'=>10,'message'=>'用户名最大长度为10个字符'],
            ['username','valiteName'],
            ['intro','required','message'=>'请填写签名'],
            ['intro','trim'],
            ['intro','string','max'=>20,'message'=>'签名最大长度为20个字符'],
            ['intro','valiteIntro'],
            ['city','required','message'=>'请填写所在地'],
            ['city','trim'],
            ['city','string','max'=>10,'message'=>'所在地最大长度10个字符'],
            ['city','valiteCity'],
            ['sex','required','message'=>'请选择性别'],
            ['sex','trim'],
            ['sex','in','range'=>[1,2],'message'=>'性别错误'],
            ['old','required','message'=>'请填写旧密码'],
            ['old','trim'],
            ['old','validOld'],
            ['new','required','message'=>'请填写密码'],
            ['new','trim'],
            ['news','required','message'=>'请填写确认密码'],
            ['news','trim'],
            ['new', 'string', 'length' => [6, 12],'message'=>'密码长度须为6-12位'],
            ['new','match','pattern' =>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9a-zA-Z]+$/','message'=>'密码须为字母和数字组合'],
            ['news', 'string', 'length' => [6, 12],'message'=>'确认密码长度须为6-12位'],
            ['news','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9a-zA-Z]+$/','message'=>'确认密码须为字母和数字组合'],
            ['new','compare','compareAttribute'=>'news','message'=>'新密码和确认密码不一致'],
        ];
    }
    function validOld($attribute){
        if(strpos($this->old,'%')!==false){
            $this->addError($attribute,'密码不得含有特殊字符');
            return false;
        }
        if(strpos($this->old,"'")!==false){
            $this->addError($attribute,'密码不得含有特殊字符');
            return false;
        }
        if(strpos($this->old,'-')!==false){
            return $this->addError($attribute,'密码不得含有特殊字符');
        }
        if(!\Yii::$app->security->validatePassword($this->old,\Yii::$app->user->identity->password)){
            return $this->addError($attribute,'旧密码错误');
        }
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

    public function valiteIntro($attribute){
        if(strpos($this->intro,'<script>')!==false){
            $this->addError($attribute,'个人简介不得含有特殊字符');
            return false;
        }
        if(strpos($this->intro,'</script>')!==false){
            $this->addError($attribute,'个人简介不得含有特殊字符');
            return false;
        }
        if(strpos($this->intro,'-')!==false){
            return $this->addError($attribute,'个人简介不得含有特殊字符');
        }
    }


    public function valiteCity($attribute){
        if(strpos($this->city,'<script>')!==false){
            $this->addError($attribute,'城市不得含有特殊字符');
            return false;
        }
        if(strpos($this->city,'</script>')!==false){
            $this->addError($attribute,'城市不得含有特殊字符');
            return false;
        }
        if(strpos($this->city,'-')!==false){
            return $this->addError($attribute,'城市不得含有特殊字符');
        }
    }

    public function attributeLabels(){
        return [
            'username' => '昵称',
            'intro' => '个人简介',
            'city'=>'所在地',
            'sex'=>'性别',
            'new'=>'新密码',
            'old'=>'旧密码',
            'news'=>'确认密码',
        ];
    }

    public function scenarios(){
        return [
            'pass' => ['old','new','news'],
            'set' => ['username','intro','city','sex'],
        ];
    }

    public function save($id){
        if(!$this->validate()){return false;}
        $user=User::findOne($id);
        $user->username=$this->username;
        $user->intro=$this->intro;
        $user->city=$this->city;
        $user->sex=$this->sex;
        return $user->save()?true:false;
    }

    public function reset($id){
        if(!$this->validate()){return false;}
        $user=User::findOne($id);
        $user->setPassword($this->new);
        return $user->save()?true:false;
    }

}