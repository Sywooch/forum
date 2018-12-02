<?php
namespace backend\models;

use yii\base\Model;
use frontend\models\Plate;

class PlateForm extends Model{

    public $fid;
    public $name;
    public $intro;
    public $img;
    public $recommend;

    public function rules(){
        return [
            ['fid','validateFid'],
            ['name','required','message'=>'请填写版区名称'],
            ['name','string','max'=>10],
            ['name','validateName'],
            ['intro','required','message'=>'请填写版区名称'],
            ['intro','string','max'=>20],
            ['intro','validateIntro'],
            ['img','validateImg'],
            ['recommend','in','range'=>[0,1]],
        ];
    }

    public function attributeLabels(){
        return [
            'fid'=>'上级版区',
            'name' => '版区名',
            'intro' => '简介',
            'img' => '版区图像',
            'recommend' => '推荐',
        ];
    }

    public function validateFid($attribute){

    }

    public function validateImg($attribute){

    }

    public function validateName($attribute){
        if(strpos($this->title,'<script>')!==false){
            $this->addError($attribute,'版区名不得含有特殊字符');
            return false;
        }
        if(strpos($this->title,'</script>')!==false){
            $this->addError($attribute,'版区名不得含有特殊字符');
            return false;
        }
        if(strpos($this->title,'-')!==false){
            return $this->addError($attribute,'版区名不得含有特殊字符');
        }
        if(strpos($this->title,"'")!==false){
            return $this->addError($attribute,'版区名不得含有特殊字符');
        }
    }

    public function validateIntro($attribute){
        if(strpos($this->title,'<script>')!==false){
            $this->addError($attribute,'版区名不得含有特殊字符');
            return false;
        }
        if(strpos($this->title,'</script>')!==false){
            $this->addError($attribute,'版区名不得含有特殊字符');
            return false;
        }
        if(strpos($this->title,'-')!==false){
            return $this->addError($attribute,'版区名不得含有特殊字符');
        }
        if(strpos($this->title,"'")!==false){
            return $this->addError($attribute,'版区名不得含有特殊字符');
        }
    }

    public function create(){
        $plateModel=new Plate();
        if(!empty($this->fid)){$plateModel->fid=$this->fid;}
        if(!empty($this->img)){$plateModel->img=$this->img;}
        $plateModel->name=$this->name;
        $plateModel->is_recommend=$this->recommend;
        $plateModel->intro=$this->intro;
        $plateModel->create_at=time();
        return $plateModel->save();
    }

    public function update($id){
        $plateModel=Plate::findOne(['id'=>$id]);
        if(!empty($this->fid)){$plateModel->fid=$this->fid;}
        if(!empty($this->img)){$plateModel->img=$this->img;}
        $plateModel->name=$this->name;
        $plateModel->is_recommend=$this->recommend;
        $plateModel->intro=$this->intro;
        $plateModel->create_at=time();
        return $plateModel->save();
    }


}