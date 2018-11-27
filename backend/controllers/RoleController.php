<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Role;
use yii\data\Pagination;
use backend\models\RoleForm;


class RoleController extends Controller{


    /**
     *角色列表
     */
    public function actionList(){

        if(Yii::$app->request->isPost||Yii::$app->request->isAjax){
            $post=Yii::$app->request->post();
            //获取所有列表
            $where[]='and';
            $where[]=['type'=>1];
            if(isset($post['name'])&&!empty($post['name'])){
                $where[]=['like','description',$post['name']];
                $pagion_param['param']['name']=$post['name'];
            }
            $query=Role::find();

            $count=$query->filterWhere($where)->count();

            $pagion_param=[
                'defaultPageSize' =>isset($post['limit'])?$post['limit']:10,
                'totalCount' =>$count,
            ];
            $pagination=new Pagination($pagion_param);

            $list=$query->select('name,description')->filterWhere($where)->orderBy(['created_at'=>SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();

            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;

            return ['code'=>0,'count'=>$count,'data'=>$list?$list:''];
        }
        return $this->render('list');
    }

    /**
     * 添加角色
     */
    public function actionAdd(){
        $model=new RoleForm();
        if($model->load(Yii::$app->request->post())&&$model->addRole()){
            Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect('index.php?r=role/add');
        }
        return $this->render('add',['model'=>$model]);
    }

    /**
     * 查看成员
     */

    public function actionView(){




    }

    /**
     * 批量删除
     */

    public function actionDel(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        if(!isset($post['id'])||empty($post['id'])){
            return ['code'=>0,'info'=>'请选择修改数据','data'=>[]];
        }
        $res=Role::deleteAll(['name'=>$post['id']]);
        return ['code'=>$res?1:0,'info'=>$res?'操作成功':'操作失败','data'=>[]];
    }




}
