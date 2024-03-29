<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use backend\models\Permission;
use backend\models\RoleForm;
use yii\filters\AccessControl;

class RoleController extends Controller{

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['list'],
                        'roles' => ['role/list'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['role/create'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['role/update'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['role/delete'],
                    ],
                ],
            ],
        ];
    }

    public function actionList(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            $where=['and',['=','type',1]];
            $post=Yii::$app->request->post();
            if(isset($post['names'])&&!empty($post['names'])){
                $where[]=['LIKE','description',$post['names']];
            }
            $query=Permission::find();
            $count=$query->filterWhere($where)->count();
            $pagination=new Pagination([
                'defaultPageSize' =>isset($post['limit'])?$post['limit']:20,
                'totalCount' =>$count,
                'params'=>['page'=>isset($post['page'])?$post['page']:1],
            ]);
            $list=$query->select('id,name,description,created_at,updated_at')->filterWhere($where)->orderBy(['id'=>SORT_ASC])->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();
            $list=$this->conversion($list);
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            return ['code'=>0,'count'=>$count,'data'=>$list?$list:[]];
        }
        return $this->render('list',['title'=>'角色列表']);
    }

    public function actionCreate(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new RoleForm(['scenario'=>'create']);
            if($model->load(Yii::$app->request->post())&&$model->create()){return ['code'=>0,'info'=>'添加成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>1,'info'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>1,'info'=>$msg,'data'=>''];
        }
        return $this->renderPartial('create',['title'=>'添加角色']);
    }

    public function actionUpdate(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new RoleForm(['scenario'=>'update']);
            if($model->load(Yii::$app->request->post())&&$model->update()){return ['code'=>0,'info'=>'编辑成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>1,'info'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>1,'info'=>$msg,'data'=>''];
        }
        $id=Yii::$app->request->get('id','');
        $info=Permission::find()->select('name,description')->filterWhere(['id'=>$id,'type'=>1])->asArray()->one();
        return $this->renderPartial('update',['title'=>'编辑角色','info'=>$info]);
    }

    public function actionDelete(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $id=Yii::$app->request->post('id',0);
            if(empty($id)){return ['code'=>1,'info'=>'请选择删除数据!','data'=>''];}
            $model=new RoleForm();
            $result=$model->delete($id);
            return ['code'=>$result?0:1,'info'=>$result?'删除成功':'删除失败','data'=>''];
        }
    }

    private function conversion($list){
        if(!empty($list)){
            foreach($list as $k=>$v){
                $list[$k]['created_at']=$v['created_at']?date('Y-m-d H:i'):'';
                $list[$k]['updated_at']=$v['updated_at']?date('Y-m-d H:i'):'';
            }
        }
        return $list;
    }

}