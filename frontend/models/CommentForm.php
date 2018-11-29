<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

class CommentForm extends Model{

    public $o;
    public $c;
    public $t;

    public function rules(){
        return [
            ['o','required','message'=>'请填写评论帖子'],
            ['o','trim'],
            ['o', 'number','min'=>1],
            ['c','required','message'=>'请填写评论内容'],
            ['c','trim'],
            ['c','string','max'=>30],
            ['c','ValiteContent'],
            ['t','required','message'=>'请填写评论类型'],
            ['t','trim'],
            ['t','in','range'=>[1,2]],
        ];
    }

    public function attributeLabels(){
        return [
            'o'=>'评论帖子',
            'c'=>'评论内容',
            't'=>'评论对象',
        ];
    }

    public function valiteContent($attribute){
        if(strpos($this->c,'<script>')!==false){
            $this->addError($attribute,'评论内容不得含有特殊字符');
            return false;
        }
        if(strpos($this->c,'</script>')!==false){
            $this->addError($attribute,'评论内容不得含有特殊字符');
            return false;
        }
        if(strpos($this->c,'-')!==false){
            return $this->addError($attribute,'评论内容不得含有特殊字符');
        }
        if(strpos($this->c,"'")!==false){
            return $this->addError($attribute,'评论内容不得含有特殊字符');
        }
        if(strpos($this->c,"<iframe>")!==false){
            return $this->addError($attribute,'评论内容不得含有特殊字符');
        }
        if(strpos($this->c,'%')!==false){
            return ['code'=>0,'msg'=>'评论内容不得含有特殊字符!','data'=>''];
        }
        $this->SensitiveWord($this->c,$attribute);
    }

    private function SensitiveWord($str,$attribute){
        $SensitiveWordArr=Yii::$app->params['SensitiveWord'];
        foreach($SensitiveWordArr as $v){
            $str=str_replace($v,str_repeat('*',strlen($v)/3),$str);
        }
        return $this->$attribute=$str;
    }

    public function getFormData($data){
        $data["CommentForm"]=$data;
        return $data;
    }


}