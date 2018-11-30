<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use frontend\models\Post;
use common\models\User;
use backend\models\PostForm;

class PostController extends Controller{

    public function actionList(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            $where=[];

            if(isset($post['name'])&&!empty($post['name'])){
                $user_id=User::find()->select('id')->where(['or',['like','email','%'.$post['name'].'%'],['like','username','%'.$post['name'].'%']])->scalar();
                $user_id=$user_id?$user_id:-1;
                $where=['OR',['=','user_id',$user_id],['=','title',$post['name']]];
            }

            $query=Post::find();

            $count=$query->filterWhere($where)->count();

            $pagination=new Pagination([
                'defaultPageSize' =>isset($post['limit'])?$post['limit']:10,
                'totalCount' =>$count,
                'params'=>['page'=>isset($post['page'])?$post['page']:1],
            ]);
            $list=$query->select('id,user_id,plate_id,title,view,comments,collection,star,essence,is_hot,tos,create_at')->with(['user','plate'])->filterWhere($where)->orderBy(['id'=>SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
            $list=$this->conversion($list);
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            return ['code'=>0,'count'=>$count,'data'=>$list?$list:[]];
        }
        return $this->render('list',['title'=>'帖子管理']);
    }

    public function actionUpdate($id){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new PostForm();
            if($model->load(Yii::$app->request->post())&&$model->update()){
                return ['code'=>0,'info'=>'编辑成功!','data'=>''];
            }
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>1,'info'=>$msg,'data'=>''];
        }
        $info=Post::find()->select('id,title,content')->where(['id'=>$id])->asArray()->one();
        return $this->renderPartial('update',['title'=>'帖子编辑','info'=>$info]);
    }

    public function actionEssence(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $posts=Yii::$app->request->post();
        if(!isset($posts['id'])||empty($posts['id'])){
            return ['code'=>0,'info'=>'请选择操作数据','data'=>[]];
        }
        if(!is_numeric($posts['id'])){
            return ['code'=>0,'info'=>'无此数据','data'=>[]];
        }
        $res=Post::updateAll(['essence'=>2],['id'=>$posts['id']]);
        return ['code'=>$res?0:1,'info'=>$res?'操作成功':'操作失败','data'=>[]];
    }

    public function actionHot(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $posts=Yii::$app->request->post();
        if(!isset($posts['id'])||empty($posts['id'])){return ['code'=>0,'info'=>'请选择操作数据','data'=>[]];}
        if(!is_numeric($posts['id'])){return ['code'=>0,'info'=>'无此数据','data'=>[]];}
        $res=Post::updateAll(['is_hot'=>2],['id'=>$posts['id']]);
        return ['code'=>$res?0:1,'info'=>$res?'操作成功':'操作失败','data'=>[]];
    }

    public function actionDelete(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $posts=Yii::$app->request->post();
        if(!isset($posts['id'])||empty($posts['id'])){return ['code'=>0,'info'=>'请选择删除数据','data'=>[]];}
        if(!is_numeric($posts['id'])){return ['code'=>0,'info'=>'无此数据','data'=>[]];}
        $postModel=new Post();
        $postModel->deletePost($posts['id']);
        return ['code'=>0,'info'=>'删除成功','data'=>[]];
        //return ['code'=>$res?0:1,'info'=>$res?'删除成功':'删除失败','data'=>[]];
    }

    public function conversion($list){
        if(!empty($list)){
            foreach($list as $k=>$v){
                $list[$k]['username']=$v['user']['username']?$v['user']['username']:$v['user']['email'];
                $list[$k]['name']=$v['plate']['name'];
                $list[$k]['essence']=$v['essence']?'<button class="layui-btn layui-btn-warm layui-btn-xs">精贴</button>':'否';
                $list[$k]['is_hot']=$v['is_hot']?'<button class="layui-btn layui-btn-danger layui-btn-xs">热帖</button>':'否';
                $list[$k]['create_at']=date('Y-m-d H:i:s',$v['create_at']);
            }
        }
        return $list;
    }

}

