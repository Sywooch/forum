<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use yii\data\Pagination;
use backend\models\UupdateForm;

class UserController extends Controller{

    public function actionList(){
        if(Yii::$app->request->isAjax){
            $get=Yii::$app->request->get();
            $where=[];
            if(isset($get['name'])&&!empty($get['name'])){
                $where=['or',['email'=>$get['name']],['username'=>$get['name']]];
            }
            $query=User::find();
            $count=$query->filterWhere($where)->count();

            $pagination=new Pagination([
                'defaultPageSize' =>isset($get['limit'])?$get['limit']:20,
                'totalCount' =>$count,
            ]);

            $list=$query->select('id,email,username,city,sex,level,experience,integral,groups,status,ip,created_at')->filterWhere($where)->orderBy(['id'=>SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
            $list=$this->conversion($list);
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            return ['code'=>0,'msg'=>'数据如下','count'=>$count,'data'=>$list?$list:[]];
        }
        return $this->render('list',['title'=>'用户管理']);
    }


    /*
     * 批量禁用
     * */
    public function actionDisable(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        if(!isset($post['id'])||empty($post['id'])){
            return ['code'=>1,'info'=>'请选择修改数据','data'=>[]];
        }
        $res=User::updateAll(['status'=>'2'],['id'=>$post['id']]);
        return ['code'=>$res?0:1,'info'=>$res?'操作成功':'操作失败','data'=>[]];
    }

    /*
     * 删除
     */
    public function actionDel(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        if(!isset($post['id'])||empty($post['id'])){
            return ['code'=>0,'info'=>'请选择修改数据','data'=>[]];
        }
        $user=User::findOne($post['id']);
        $res=$user->delete();
        return ['code'=>$res?1:0,'info'=>$res?'操作成功':'操作失败','data'=>[]];
    }

    /*
     *编辑
     */
    public function actionEdit(){
        $model=new UupdateForm();

        if($model->load(Yii::$app->request->post())&&$model->updates()){
            Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect('index.php?r=user/edit&id='.$model->id);
        }
        $gets=Yii::$app->request->get();
        $user=User::findOne($gets['id']);
        return $this->renderPartial('edit',['model'=>$model,'user'=>$user]);
    }

    public function conversion($list){
        foreach($list as $k=>$v){
            $list[$k]['created_at']=$v['created_at']?date("Y-m-d H:i:s",$v['created_at']):'';
            switch($v['groups']){
                case '0':
                    $list[$k]['groups']='普通用户';
                    break;
                case "1":
                    $list[$k]['groups']='管理员';
                    break;
            }
            switch($v['status']){
                case '1':
                    $list[$k]['status']='待激活';
                    break;
                case "2":
                    $list[$k]['status']='禁用';
                    break;
                case "3":
                    $list[$k]['status']='暂停';
                    break;
                case "10":
                    $list[$k]['status']='正常';
                    break;
            }
            switch($v['sex']){
                case '1':
                    $list[$k]['sex']='男';
                    break;
                case '2':
                    $list[$k]['sex']='女';
                    break;
            }
        }
        return $list;
    }

}