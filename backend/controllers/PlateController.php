<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use frontend\models\Plate;
use backend\models\PlateForm;
use yii\filters\AccessControl;

class PlateController extends Controller{

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['list'],
                        'roles' => ['plate/list'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['plate/create'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['plate/update'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['close'],
                        'roles' => ['plate/close'],
                    ],
                ],
            ],
        ];
    }

    public function actionList(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            $where=[];
            if(isset($post['name'])&&!empty($post['name'])){
                $where=['like', 'name', 'php'];
            }
            $query=Plate::find();
            $count=$query->filterWhere($where)->count();
            $pagination=new Pagination([
                'defaultPageSize' =>isset($post['limit'])?$post['limit']:10,
                'totalCount' =>$count,
                'params'=>['page'=>isset($post['page'])?$post['page']:1],
            ]);
            $list=$query->select('id,fid,img,name,intro,totals,is_recommend,create_at')->filterWhere($where)->orderBy(['id'=>SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
            $list=$this->conversion($list,$where);
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            return ['code'=>0,'count'=>$count,'data'=>$list?$list:[]];
        }
        return $this->render('list',['title'=>'版区管理']);
    }

    public function actionCreate(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new PlateForm();
            if($model->load(Yii::$app->request->post())&&$model->create()){return ['code'=>0,'info'=>'新增成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>0,'msg'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>0,'info'=>$msg,'data'=>''];
        }
        $plates=Plate::find()->select(['id','name'])->where(['fid'=>0])->asArray()->all();
        return $this->renderPartial('create',['title'=>'版区新增','plates'=>$plates]);
    }

    public function actionUpdate(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new PlateForm();
            if($model->load(Yii::$app->request->post())&&$model->update(Yii::$app->request->post('id'))){return ['code'=>0,'info'=>'修改成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>0,'info'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>0,'info'=>$msg,'data'=>''];
        }
        $plates=Plate::find()->select(['id','name'])->where(['fid'=>0])->asArray()->all();
        $id=Yii::$app->request->get('id');
        $info=Plate::find()->select(['id','fid','name','img','is_recommend','intro'])->where(['id'=>$id])->asArray()->one();
        return $this->renderPartial('update',['title'=>'版区编辑','plates'=>$plates,'info'=>$info]);
    }

    public function actionClose(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $id=Yii::$app->request->post('id','');
        if(empty($id)){return ['code'=>1,'info'=>'请选择关闭版区','data'=>''];}
        $info=Plate::findOne($id);
        $res=Plate::updateAll(['status' =>1], ['like', 'email', '@example.com']);
        return ['code'=>$res?0:1,'info'=>$res?'操作成功':'操作失败','data'=>''];
    }

    private function conversion($list,$where=[]){
        $lists=[];
        if(!empty($where)){
            if(!empty($list)){
                foreach($list as $k=>$v){
                    $list[$k]['is_recommend']=$v['is_recommend']?'推荐':'不推荐';
                    $list[$k]['create_at']=$v['create_at']?date("Y-m-d H:i"):'';
                }
            }
            return $list;
        }
        if(!empty($list)){
            foreach($list as $k=>$v){
                if($v['fid']==0){
                    $v['is_recommend']=$v['is_recommend']?'推荐':'不推荐';
                    $v['create_at']=$v['create_at']?date("Y-m-d H:i"):'';
                    $lists[]=$v;
                    foreach($list as $ks=>$vs){
                        if($vs['fid']==$v['id']){
                            $vs['is_recommend']=$vs['is_recommend']?'推荐':'不推荐';
                            $vs['create_at']=$vs['create_at']?date("Y-m-d H:i"):'';
                            $lists[]=$vs;
                        }
                    }
                }
            }
        }
        return $lists;
    }


}