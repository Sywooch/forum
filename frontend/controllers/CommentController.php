<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Comment;
use frontend\models\Post;
use frontend\models\CommentForm;

class CommentController extends Controller{

    public function actionCreate(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id=Yii::$app->user->identity;
        if(!$id){return ['code'=>0,'msg'=>'请登陆后评论!','data'=>''];}
        $data=Yii::$app->request->post();
        $model=new CommentForm();
        $datas=$model->getFormData($data);
        if(!($model->load($datas)&&$model->validate())){
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>0,'msg'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){
                $msg=$v[0];
            }
            return ['code'=>0,'msg'=>$msg,'data'=>''];
        }
        $PostModel=new Post();
        $CommentModel=new Comment();
        $PostId=$data['o'];
        $Buser='';
        if($data['t']==2){
            $CommentDetail=$CommentModel->getOne($data['o']);
            if(empty($CommentDetail)){return ['code'=>0,'msg'=>'评论不存在','data'=>''];};
            if($id->id==$CommentDetail['user_id']){return ['code'=>0,'msg'=>'不能对自己回复','data'=>''];}
            $Buser=$CommentDetail;
            $PostId=$CommentDetail['post_id'];
        }
        $frequency=$CommentModel->commentFrequency(Yii::$app->user->identity,$PostId);
        if($frequency!==true){return ['code'=>0,'msg'=>$frequency,'data'=>''];}
        $PostExist=$PostModel->getPostIsExist($PostId);
        if(!$PostExist){return ['code'=>0,'msg'=>'评论帖子不存在','data'=>''];}
        $str=$CommentModel->getAjaxContent($id,$data,$PostExist['tos'],$Buser);
        $CommentModel->setCommentWork([
            'uid'=>$id->id,
            'type'=>$data['t'],
            'pid'=>$PostId,
            'bobj'=>$data['o'],
            'content'=>$data['c'],
            'tos'=>$PostExist['tos'],
            'puid'=>$PostExist['user_id'],
            'notices'=>$PostExist['notices'],
        ]);
        return ['code'=>1,'msg'=>'评论成功!','data'=>['id'=>$data['o'],'content'=>$str]];
    }


}