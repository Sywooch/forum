<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Collection;
use frontend\models\Post;

class CollectionController extends Controller{

    public function actionCollection(){
        $post=\Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!isset($post['id'])||empty($post['id'])){
            return ['code'=>0,'msg'=>'没有收藏帖子!','data'=>''];
        }
        $pid=$post['id'];
        $id=\Yii::$app->user->id;
        if(!$id){return ['code'=>0,'msg'=>'请登陆后操作!','data'=>''];}

        $counts=Collection::find()->where(['and',['user_id'=>$id],['post_id'=>$pid]])->count();
        if($counts>0){return ['code'=>0,'msg'=>'您已经收藏过了!','data'=>''];}

        $redis=Yii::$app->redis;
        $isExist=$redis->sismember('send_post_id',$post['id']);
        if(!$isExist){return ['code'=>0,'msg'=>'无此帖子!','data'=>''];}
        $models=new Collection();
        $models->user_id=$id;
        $models->post_id=$pid;
        $models->created_t=time();
        $res=$models->save();




        return ['code'=>$res?1:0,'msg'=>$res?'收藏成功!':'收藏失败,请重试','data'=>$pid];
    }

    public function actionUncollect(){
        $post=\Yii::$app->request->post();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!isset($post['id'])||empty($post['id'])){
            return ['code'=>0,'msg'=>'没有收藏帖子!','data'=>''];
        }
        $pid=$post['id'];
        $id=\Yii::$app->user->id;
        if(!$id){
            return ['code'=>0,'msg'=>'请登陆后操作!','data'=>''];
        }

        $counts=Collection::find()->where(['and',['user_id'=>$id],['post_id'=>$pid]])->one();
        if(!$counts){
            return ['code'=>0,'msg'=>'您还没有收藏过!','data'=>''];
        }
        $pmodel=Post::findOne($post['id']);
        if(!$pmodel){
            return ['code'=>0,'msg'=>'无此帖子!','data'=>''];
        }
        $res=$counts->delete();
        $res1=$pmodel->updateCounters(['collection' =>-1]);
        return ['code'=>$res&&$res1?1:0,'msg'=>$res&&$res1?'取消成功!':'取消失败,请重试','data'=>$pid];
    }


}