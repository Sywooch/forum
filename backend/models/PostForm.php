<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use frontend\models\Post;

class PostForm extends Model{

    public $id;
    public $title;
    public $content;

    public function rules(){
        return [
            ['id','required','message'=>'请选择编辑帖子'],
            ['id','valiteId'],
            ['title','required','message'=>'请填写标题'],
            ['title','trim'],
            ['title','string','max'=>30,'message'=>'标题最多30个字'],
            ['title','valiteTitle'],
            ['content','required','message'=>'请填写内容'],
            ['content','trim'],
            ['title','string','max'=>10000,'message'=>'内容最多10000个字符'],
            ['content','valiteContent'],
        ];
    }

    public function attributeLabels(){
        return [
            'title' => '标题',
            'content' => '内容',
        ];
    }

    public function valiteId($attribute){
        $redis=Yii::$app->redis;
        $res=$redis->sismember('send_post_id',$this->id);
        if(!$res){$this->addError($attribute, '帖子不存在！');return false;}
    }

    public function valiteTitle($attribute){
        if(strpos($this->title,'<script>')!==false){
            $this->addError($attribute,'标题不得含有特殊字符');
            return false;
        }
        if(strpos($this->title,'</script>')!==false){
            $this->addError($attribute,'标题不得含有特殊字符');
            return false;
        }
        if(strpos($this->title,'-')!==false){
            return $this->addError($attribute,'标题不得含有特殊字符');
        }
        if(strpos($this->title,"'")!==false){
            return $this->addError($attribute,'标题不得含有特殊字符');
        }
        $this->SensitiveWord($this->title,$attribute);
    }

    public function valiteContent($attribute){
        if(strpos($this->content,'<script>')!==false){
            $this->addError($attribute,'内容不得含有特殊字符');
            return false;
        }
        if(strpos($this->content,'</script>')!==false){
            $this->addError($attribute,'内容不得含有特殊字符');
            return false;
        }
        if(strpos($this->content,'iframe')!==false){
            $this->addError($attribute,'内容不得含有特殊字符');
            return false;
        }
        $this->SensitiveWord($this->content,$attribute);
    }

    private function SensitiveWord($str,$attribute){
        $SensitiveWordArr=Yii::$app->params['SensitiveWord'];
        foreach($SensitiveWordArr as $v){
            $str=str_replace($v,str_repeat('*',strlen($v)/3),$str);
        }
        return $this->$attribute=$str;
    }

    public function update(){
        if(!$this->validate()){return false;}
        $postModel=Post::findOne(['id'=>$this->id]);
        $postModel->title=$this->title;
        $postModel->content=$this->content;
        $res=$postModel->save();
        return $res?true:false;
    }

}