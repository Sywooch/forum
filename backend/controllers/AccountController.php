<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use backend\models\User;
use backend\models\AccountForm;
use backend\models\Permission;
use backend\models\Assignment;

class AccountController extends Controller{

    public function actionList(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            $where=[];
            if(isset($post['names'])&&!empty($post['names'])){
                $where=['LIKE','username',$post['names']];
            }
            $query=User::find();
            $count=$query->filterWhere($where)->count();
            $pagination=new Pagination([
                'defaultPageSize' =>isset($post['limit'])?$post['limit']:10,
                'totalCount' =>$count,
                'params'=>['page'=>isset($post['page'])?$post['page']:1],
            ]);
            $list=$query->select('id,username,created_at')->with(['assignment.item'])->filterWhere($where)->orderBy(['id'=>SORT_ASC])->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();
            $list=$this->conversion($list);
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            return ['code'=>0,'count'=>$count,'data'=>$list?$list:[]];
        }
        return $this->render('list',['title'=>'用户列表']);
    }

    public function actionCreate(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new AccountForm(['scenario'=>'create']);
            if($model->load(Yii::$app->request->post())&&$model->create()){return ['code'=>0,'info'=>'添加成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>1,'info'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>1,'info'=>$msg,'data'=>''];
        }
        $roleList=Permission::find()->select('name,description')->where(['type'=>1])->asArray()->all();
        return $this->renderPartial('create',['title'=>'添加管理员','lists'=>$roleList]);
    }

    public function actionUpdate(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new AccountForm(['scenario'=>'update']);
            if($model->load(Yii::$app->request->post())&&$model->update()){return ['code'=>0,'info'=>'编辑成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>1,'info'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>1,'info'=>$msg,'data'=>''];
        }
        $id=Yii::$app->request->get('id','');
        $info=User::find()->select('id,username,password')->filterWhere(['id'=>$id])->asArray()->one();
        if(!empty($info)){
            $role=Assignment::find()->select('item_name')->where(['user_id'=>$info['id']])->asArray()->column();
            $info['role']=$role[0];
        }
        $roleList=Permission::find()->select('name,description')->where(['type'=>1])->asArray()->all();
        return $this->renderPartial('update',['title'=>'编辑菜单','info'=>$info,'lists'=>$roleList]);
    }

    public function actionDelete(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $id=Yii::$app->request->post('id',0);
            if(empty($id)){return ['code'=>1,'info'=>'请选择删除数据!','data'=>''];}
            $model=new AccountForm(['scenario'=>'delete']);
            $model->id=$id;
            $result=$model->delete();
            return ['code'=>$result?0:1,'info'=>$result?'删除成功':'删除失败','data'=>''];
        }
    }

    private function conversion($list){
        if(!empty($list)){
            foreach($list as $k=>$v){
                $list[$k]['created_at']=$v['created_at']?date("Y-m-d H:i"):'';
                $list[$k]['role']='';
                if(!empty($v['assignment'])){
                    $list[$k]['role']=$v['assignment']['item']['description'];
                }
                unset($list[$k]['assignment']);
            }
        }
        return $list;
    }

}