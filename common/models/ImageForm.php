<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;

class ImageForm extends Model{

    public $imageFile;
    public $types=['avatar','images'];
    private $LocalLocation;

    public function rules(){
        return [
            ['imageFile','image','extensions' =>'png,jpg,jpeg,gif','mimeTypes'=>'image/png,image/jpeg,image/gif','maxSize'=>1024*300],
        ];
    }

    public function save($type){
        if(!$this->validate()){return false;}
        $source='abcdefghijklmnopqrstuvwxyz';
        $str='';
        for($i=0;$i<6;++$i){$str.=substr($source,mt_rand(0,strlen($source)-1),1);}
        $filename=date("ymdHis").'_'.$str;
        $local=$this->typeLocal($type);
        if(!is_dir($local)){mkdir($local,0755,true);}
        $this->LocalLocation=$local.'/'. $filename . '.' . $this->imageFile->extension;
        $res=$this->imageFile->saveAs($this->LocalLocation);
        if(!$res){return false;}
        if($type=='avatar'){return $this->avatar();}
        return ltrim($this->LocalLocation,'.');
    }

    private function avatar(){
        Image::resize($this->LocalLocation,90,90)->save($this->LocalLocation);
        $local=ltrim($this->LocalLocation,'.');
        $user=Yii::$app->user->identity;
        $host=Yii::$app->request->hostInfo;
        $user->avatar=$host.$local;
        return $user->save();
    }

    private function typeLocal($type){
        $lists=[
            'avatar'=>'./images/avatar/'.date("Ym"),
            'images'=>'./images/bbs/'.date("Ym"),
        ];
        return $lists[$type];
    }


}