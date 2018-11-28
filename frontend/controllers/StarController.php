<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Star;
use frontend\models\Post;

class StarController extends Controller{

    public function actionStar(){
        $post=Yii::$app->request->post();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!isset($post['id'])||empty($post['id'])){
            return ['code'=>0,'msg'=>'帖子不存在!','data'=>''];
        }
        $pid=$post['id'];
        $id=Yii::$app->user->id;
        if(!$id){return ['code'=>0,'msg'=>'请登陆后操作!','data'=>''];}

        $redis=Yii::$app->redis;
        $isExist=$redis->sismember('send_post_id',$pid);
        if(!$isExist){return ['code'=>0,'msg'=>'无此帖子!','data'=>''];}

        $counts=Star::find()->where(['and',['user_id'=>$id],['post_id'=>$pid]])->count();
        if($counts>0){return ['code'=>0,'msg'=>'您已经点赞过了!','data'=>''];}

        $models=new Star();
        $models->user_id=$id;
        $models->post_id=$pid;
        $models->created_t=time();
        $res=$models->save();
        if($res){
            $postModel=new Post();
            $postModel->updateStar($pid,1);
        }
        return ['code'=>$res?1:0,'msg'=>$res?'点赞成功!':'点赞失败,请重试','data'=>$pid];
    }

    public function actionUnstar(){
        $post=Yii::$app->request->post();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!isset($post['id'])||empty($post['id'])){
            return ['code'=>0,'msg'=>'帖子不存在!','data'=>''];
        }
        $pid=$post['id'];
        $id=Yii::$app->user->id;
        if(!$id){return ['code'=>0,'msg'=>'请登陆后操作!','data'=>''];}

        $redis=Yii::$app->redis;
        $isExist=$redis->sismember('send_post_id',$pid);
        if(!$isExist){return ['code'=>0,'msg'=>'无此帖子!','data'=>''];}

        $counts=Star::find()->where(['and',['user_id'=>$id],['post_id'=>$pid]])->one();
        if(!$counts){return ['code'=>0,'msg'=>'您还没有点赞过!','data'=>''];}
        $res=$counts->delete();
        if($res){
            $postModel=new Post();
            $postModel->updateStar($pid,2);
        }
        return ['code'=>$res?1:0,'msg'=>$res?'取消成功!':'取消失败,请重试','data'=>$pid];
    }

}