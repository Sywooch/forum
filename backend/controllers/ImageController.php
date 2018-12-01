<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\ImageForm;
use yii\web\UploadedFile;

class ImageController extends Controller{

    public function actionUpload(){
        //if(!Yii::$app->user->identity){return json_encode(['code'=>1,'state'=>'请登陆后操作','url'=>null,'msg'=>'请登陆后操作!','data'=>'']);}
        if(Yii::$app->request->isPost){
            $model = new ImageForm();
            $model->imageFile=UploadedFile::getInstance($model,'imageFile');
            if(empty($model->imageFile)){return json_encode(['code'=>1,'state'=>'违规上传','url'=>null,'msg'=>'违规上传!','data'=>'']);}
            $posts=Yii::$app->request->post();
            if(!isset($posts['type'])||empty($posts['type'])||!in_array($posts['type'],$model->types)){
                return json_encode(['code'=>1,'state'=>'违规上传','url'=>null,'msg'=>'违规上传!','data'=>'']);
            }
            if(!$local=$model->save($posts['type'])){
                $errors=$model->getErrors();
                return json_encode(['code'=>1,'state'=>empty($errors)?'上传失败!':$errors['imageFile'][0],'msg'=>empty($errors)?'上传失败!':$errors['imageFile'][0],'url'=>null,'data'=>'']);
            }
            return json_encode(['code'=>0,'url'=>$local,'msg'=>'上传成功!','data'=>'']);
        }
    }

}