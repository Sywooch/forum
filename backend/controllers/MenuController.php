<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Menu;
use yii\data\Pagination;
use backend\models\MenuForm;

class MenuController extends Controller{

    public function actionList(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            $where=[];
            if(isset($post['names'])&&!empty($post['names'])){
                $where=['=','menu_name',$post['names']];
            }
            $query=Menu::find();
            $count=$query->filterWhere($where)->count();
            $pagination=new Pagination([
                'defaultPageSize' =>isset($post['limit'])?$post['limit']:10,
                'totalCount' =>$count,
                'params'=>['page'=>isset($post['page'])?$post['page']:1],
            ]);
            $list=$query->select('id,menu_name,menu_url,pid,created_at,updated_at')->filterWhere($where)->orderBy(['id'=>SORT_ASC])->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();
            $list=$this->conversion($list);
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            return ['code'=>0,'count'=>$count,'data'=>$list?$list:[]];

        }
        return $this->render('list',['title'=>'菜单列表']);
    }

    public function actionCreate(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new MenuForm(['scenario'=>'create']);
            if($model->load(Yii::$app->request->post())&&$model->create()){return ['code'=>0,'info'=>'添加成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>1,'info'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>1,'info'=>$msg,'data'=>''];
        }
        $lists=Menu::find()->select('id,menu_name')->where(['pid'=>0])->limit(200)->asArray()->all();
        return $this->renderPartial('create',['title'=>'添加菜单','lists'=>$lists]);
    }

    public function actionUpdate(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $model=new MenuForm(['scenario'=>'update']);
            if($model->load(Yii::$app->request->post())&&$model->update()){return ['code'=>0,'info'=>'编辑成功','data'=>''];}
            $errors=$model->getErrors();
            if(empty($errors)){return ['code'=>1,'info'=>'网络错误请重试!','data'=>''];}
            foreach($model->getErrors() as $v){$msg=$v[0];}
            return ['code'=>1,'info'=>$msg,'data'=>''];
        }
        $id=Yii::$app->request->get('id','');
        $lists=Menu::find()->select('id,menu_name')->where(['pid'=>0])->limit(200)->asArray()->all();
        $info=Menu::find()->select('id,pid,menu_name,menu_url')->filterWhere(['id'=>$id])->asArray()->one();
        return $this->renderPartial('update',['title'=>'编辑菜单','lists'=>$lists,'info'=>$info]);
    }

    public function actionDelete(){
        if(Yii::$app->request->isPost){
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $id=Yii::$app->request->post('id',0);
            if(empty($id)){return ['code'=>1,'info'=>'请选择删除数据!','data'=>''];}
            $menuModel=Menu::findOne(['id'=>$id]);
            $result=$menuModel->delete();
            return ['code'=>$result?0:1,'info'=>$result?'删除成功':'删除失败','data'=>''];
        }
    }

    private function conversion($list){
        $lists=[];
        if(!empty($list)){
            foreach($list as $k=>$v){
                if($v['pid']==0){
                    $v['created_at']=$v['created_at']?date("Y-m-d H:i"):'';
                    $v['updated_at']=$v['updated_at']?date("Y-m-d H:i"):'';
                    $lists[]=$v;
                    foreach($list as $ks=>$vs){
                        if($vs['pid']==$v['id']){
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