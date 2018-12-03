<?php
namespace backend\controllers;


use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use backend\models\Permission;
use backend\models\PermissionForm;
use backend\models\TakeForm;

class PermissionController extends Controller{

    public function actionList(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            $where=['and',['=','type',2]];
            $post=Yii::$app->request->post();
            if(isset($post['names'])&&!empty($post['names'])){
                $where[]=['LIKE','description',$post['names']];
            }
            $query=Permission::find();
            $count=$query->filterWhere($where)->count();
            $pagination=new Pagination([
                'defaultPageSize' =>isset($post['limit'])?$post['limit']:100,
                'totalCount' =>$count,
                'params'=>['page'=>isset($post['page'])?$post['page']:1],
            ]);
            $list=$query->select('id,fid,name,description,created_at,updated_at')->filterWhere($where)->orderBy(['id'=>SORT_ASC])->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();
            $list=$this->conversion($list);
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            return ['code'=>0,'count'=>$count,'data'=>$list?$list:[]];
        }
        return $this->render('list',['title'=>'权限列表']);
    }

    public function actionCreate(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new PermissionForm(['scenario'=>'create']);
            if($model->load(Yii::$app->request->post())&&$model->create()){return ['code'=>0,'info'=>'添加成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>1,'info'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>1,'info'=>$msg,'data'=>''];
        }
        $lists=Permission::find()->select('id,description')->where(['and',['=','type',2],['=','fid',0]])->limit(100)->asArray()->all();
        return $this->renderPartial('create',['title'=>'添加权限','lists'=>$lists]);
    }

    public function actionUpdate(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new PermissionForm(['scenario'=>'update']);
            if($model->load(Yii::$app->request->post())&&$model->update()){return ['code'=>0,'info'=>'编辑成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>1,'info'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>1,'info'=>$msg,'data'=>''];
        }
        $id=Yii::$app->request->get('id','');
        $lists=Permission::find()->select('id,description')->where(['and',['=','type',2],['=','fid',0]])->limit(100)->asArray()->all();
        $info=Permission::find()->select('id,fid,name,description')->filterWhere(['id'=>$id])->asArray()->one();
        return $this->renderPartial('update',['title'=>'编辑权限','lists'=>$lists,'info'=>$info]);
    }

    public function actionDelete(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $id=Yii::$app->request->post('id',0);
            if(empty($id)){return ['code'=>1,'info'=>'请选择删除数据!','data'=>''];}
            $model=new PermissionForm(['scenario'=>'update']);
            $result=$model->delete($id);
            return ['code'=>$result?0:1,'info'=>$result?'删除成功':'删除失败','data'=>''];
        }
    }

    public function actionTake(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new TakeForm();
            $model->authids=Yii::$app->request->post('authids');
            if($model->load(Yii::$app->request->post())&&$model->take()){return ['code'=>0,'info'=>'授权成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>1,'info'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>1,'info'=>$msg,'data'=>''];
        }
        return $this->renderPartial('take',['title'=>'授权']);
    }

    public function actionTree(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $permissionModel=new Permission();
            $data=$permissionModel->getTree('admin');
            return ['code','info'=>'列表如下','data'=>$data];
        }
    }

    private function conversion($list){
        $lists=[];
        if(!empty($list)){
            foreach($list as $k=>$v){
                if($v['fid']==0){
                    $v['created_at']=$v['created_at']?date("Y-m-d H:i"):'';
                    $v['updated_at']=$v['updated_at']?date("Y-m-d H:i"):'';
                    $lists[]=$v;
                    foreach($list as $ks=>$vs){
                        if($vs['fid']==$v['id']){
                            $vs['created_at']=$vs['created_at']?date("Y-m-d H:i"):'';
                            $vs['updated_at']=$vs['updated_at']?date("Y-m-d H:i"):'';
                            $lists[]=$vs;
                        }
                    }
                }
            }
        }
        return $lists;
    }

}