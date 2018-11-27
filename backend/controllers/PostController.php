<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use frontend\models\Post;
use common\models\User;

class PostController extends Controller{

    public function actionList(){

        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){

            $post=Yii::$app->request->post();
            $where[]='and';

            if(isset($post['name'])&&!empty($post['name'])){
                $user_id=User::find('id')->where(['or',['like','email',$post['name']],['like','username',$post['name']]])->scalar();
                $user_id=$user_id?$user_id:'-1';
                $where[]=['or',['user_id'=>$user_id],['like','a.title',$post['name']]];
            }

            $query=Post::find();

            $count=$query->alias('a')->filterWhere($where)->count();

            $pagion_param=[
                'defaultPageSize' =>isset($post['limit'])?$post['limit']:10,
                'totalCount' =>$count,
            ];
            $pagination=new Pagination($pagion_param);

            $list=$query->alias('a')->select('a.id,a.user_id,a.plate_id,a.title,a.view,a.comments,a.collection,a.star,a.essence,a.is_hot,a.tos,a.create_at')->filterWhere($where)->with(['user','plate'])->orderBy(['id'=>SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();

            foreach($list as $k=>$v){
                $list[$k]['username']=$v['user']['username']?$v['user']['username']:$v['user']['email'];
                $list[$k]['name']=$v['plate']['name'];
                $list[$k]['essence']=$v['essence']?'精贴':'';
                $list[$k]['is_hot']=$v['is_hot']?'热帖':'';
                $list[$k]['create_at']=date('Y-m-d H:i:s',$v['create_at']);
            }

            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;

            return ['code'=>0,'count'=>$count,'data'=>$list?$list:''];
        }

        return $this->render('list');
    }


    public function actionReview(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $posts=Yii::$app->request->post();
        if(!isset($posts['id'])||empty($posts['id'])){
            return ['code'=>0,'info'=>'请选择操作数据','data'=>[]];
        }
        if(!is_numeric($posts['id'])){
            return ['code'=>0,'info'=>'无此数据','data'=>[]];
        }
        $res=Post::updateAll(['review'=>2],['id'=>$posts['id']]);

        return ['code'=>0,'info'=>$res?'操作成功':'操作失败','data'=>[]];
    }


    public function actionEssence(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $posts=Yii::$app->request->post();
        if(!is_numeric($posts['id'])){
            return ['code'=>0,'info'=>'无此数据','data'=>[]];
        }
        $res=Post::updateAll(['essence'=>2],['id'=>$posts['id']]);
        return ['code'=>0,'info'=>$res?'操作成功':'操作失败','data'=>[]];
    }

    public function actionHot(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $posts=Yii::$app->request->post();
        if(!is_numeric($posts['id'])){
            return ['code'=>0,'info'=>'无此数据','data'=>[]];
        }
        $res=Post::updateAll(['is_hot'=>2],['id'=>$posts['id']]);
        return ['code'=>0,'info'=>$res?'操作成功':'操作失败','data'=>[]];
    }

    public function actionDel(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $posts=Yii::$app->request->post();
        if(!is_numeric($posts['id'])){
            return ['code'=>0,'info'=>'无此数据','data'=>[]];
        }
        $post=Post::findOne($posts['id']);
        $res=$post->delete();
        return ['code'=>$res?1:0,'info'=>$res?'删除成功':'删除失败','data'=>[]];
    }




}

