<?php
namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\Star;
use frontend\models\Post;

class StarController extends Controller{

    public function actionStar(){
        $post=\Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!isset($post['id'])||empty($post['id'])){
            return ['code'=>0,'msg'=>'你要点赞的东西呢!','data'=>''];
        }
        $pid=$post['id'];
        $id=\Yii::$app->user->id;
        if(!$id){
            return ['code'=>0,'msg'=>'请登陆后操作!','data'=>''];
        }
        //查询是否收藏过
        $counts=Star::find()->where(['and',['user_id'=>$id],['post_id'=>$pid]])->count();
        if($counts>0){
            return ['code'=>0,'msg'=>'您已经点赞过了!','data'=>''];
        }
        $pmodel=Post::findOne($post['id']);
        if(!$pmodel){
            return ['code'=>0,'msg'=>'无此帖子!','data'=>''];
        }
        $models=new Star();
        $models->user_id=$id;
        $models->post_id=$pid;
        $models->created_t=time();
        $res=$models->save();
        $res1=$pmodel->updateCounters(['star' => 1]);
        return ['code'=>$res&&$res1?1:0,'msg'=>$res&&$res1?'点赞成功!':'点赞失败,请重试','data'=>$pid];
    }

    public function actionUnstar(){
        $post=\Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!isset($post['id'])||empty($post['id'])){
            return ['code'=>0,'msg'=>'你要点赞的东西呢!','data'=>''];
        }
        $pid=$post['id'];
        $id=\Yii::$app->user->id;
        if(!$id){
            return ['code'=>0,'msg'=>'请登陆后操作!','data'=>''];
        }
        $counts=Star::find()->where(['and',['user_id'=>$id],['post_id'=>$pid]])->one();
        if(!$counts){
            return ['code'=>0,'msg'=>'您还没有点赞过!','data'=>''];
        }
        $pmodel=Post::findOne($post['id']);
        if(!$pmodel){
            return ['code'=>0,'msg'=>'无此帖子!','data'=>''];
        }
        $res=$counts->delete();
        $res1=$pmodel->updateCounters(['star' =>-1]);
        return ['code'=>$res&&$res1?1:0,'msg'=>$res&&$res1?'取消成功!':'取消失败,请重试','data'=>$pid];
    }

}