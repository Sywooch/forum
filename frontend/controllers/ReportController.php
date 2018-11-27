<?php
namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\Report;

class ReportController extends Controller{

    public function actionReport(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id=\Yii::$app->user->id;
        if(!$id){
            return ['code'=>0,'msg'=>'请登陆后操作!','data'=>''];
        }
        $post=\Yii::$app->request->post();
        if(!isset($post['id'])||empty($post['id'])){
            return ['code'=>0,'msg'=>'请选择举报帖子!','data'=>''];
        }
        if(!is_numeric($post['id'])){
            return ['code'=>0,'msg'=>'举报评论不存在!','data'=>''];
        }
        if(!isset($post['rs'])||empty($post['rs'])){
            return ['code'=>0,'msg'=>'请选择举报原因!','data'=>''];
        }
        if(!in_array($post['rs'],array(1,2,3,4,5))){
            return ['code'=>0,'msg'=>'无此举报原因!','data'=>''];
        }
        if(isset($post['cn'])&&!empty($post['cn'])){
            if(strpos($post['cn'],'-')!==false){
                return ['code'=>0,'msg'=>'举报内容不得含有特殊字符!','data'=>''];
            }
            if(strpos($post['cn'],'%')!==false){
                return ['code'=>0,'msg'=>'举报内容不得含有特殊字符!','data'=>''];
            }
            if(strpos($post['cn'],'<script>')!==false){
                return ['code'=>0,'msg'=>'举报内容不得含有特殊字符!','data'=>''];
            }
            if(strpos($post['cn'],'</script>')!==false){
                return ['code'=>0,'msg'=>'举报内容不得含有特殊字符!','data'=>''];
            }
            if(strlen($post['cn'])>60){
                return ['code'=>0,'msg'=>'举报内容须在20字内!','data'=>''];
            }
        }
        $report=Report::find()->select('created_at')->where(['comm_id'=>$post['id']])->scalar();
        if($report){
            return ['code'=>0,'msg'=>'此评论已被举报!','data'=>''];
        }
        $report=new Report();
        $report->user_id=\Yii::$app->user->id;
        $report->comm_id=$post['id'];
        $report->type=$post['rs'];
        if(!empty($post['cn'])){
            $report->content=$post['cn'];
        }
        $report->created_at=time();
        $res=$report->save();
        return ['code'=>$res?1:0,'msg'=>$res?'举报成功!':'举报失败,请重试','data'=>''];
    }

    public function actionCheck(){
        $id=\Yii::$app->user->id;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['code'=>$id?1:0,'msg'=>$id?'ok':'请登陆后操作!','data'=>''];
    }

}