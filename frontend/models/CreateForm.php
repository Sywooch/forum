<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\Plate;
use frontend\models\Post;
use frontend\models\Draft;
use frontend\jobs\SendPostJob;
use common\models\User;

class CreateForm extends Model{

    public $plate;
    public $title;
    public $content;
    public $price;
    public $author=false;
    public $reply=false;

    public function rules(){
        return [
            ['plate','required','message'=>'请选择所在版区'],
            ['plate','integer','message'=>'不存在该版区'],
            ['plate','exist','targetClass'=>'\frontend\models\Plate','targetAttribute'=>'id','message'=>'不存在该版区'],
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
            'plate' => '版区',
            'title' => '标题',
            'content' => '内容',
        ];
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
        if(strpos($this->content,'iframe')!==false){
            $this->addError($attribute,'标题不得含有特殊字符');
            return false;
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

    public function createPost(){
        if(!$this->validate()){return false;}
        //$PostModel=new Post();
        //$PostFrequency=$PostModel->postFrequency(Yii::$app->user->identity);
        //if($PostFrequency!==true){$this->addError('title',$PostFrequency);return false;}
        $res=Yii::$app->queue1->push(new SendPostJob([
            'score'=>Yii::$app->params['postIntegral'],
            'id'=>Yii::$app->user->id,
            'pid'=>$this->plate,
            'data'=>[
                'uid'=>Yii::$app->user->id,
                'plate_id'=>$this->plate,
                'title'=>$this->title,
                'content'=>$this->content,
                'notices'=>$this->reply?1:2,
                'create_at'=>time(),
            ]
        ]));
        return $res?true:false;
    }

}