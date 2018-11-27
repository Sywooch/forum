<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use common\models\User;
use yii\data\Pagination;
use backend\models\UupdateForm;

class UserController extends Controller{

    public function actionList(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            //获取所有列表
            $where=[];
            if(isset($post['name'])&&!empty($post['name'])){
                $where=['or',['email'=>$post['name']],['username'=>$post['name']]];
                $pagion_param['param']['name']=$post['name'];
            }

            $query=User::find();
            $count=$query->filterWhere($where)->count();

            $pagion_param=[
                'defaultPageSize' =>isset($post['limit'])?$post['limit']:10,
                'totalCount' =>$count,
            ];
            $pagination=new Pagination($pagion_param);

            $list=$query->select('id,email,username,city,sex,level,experience,integral,groups,status,ip,created_at')->filterWhere($where)->orderBy(['id'=>SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
            foreach($list as $k=>$v){
                $list[$k]['username']=$v['username']?$v['username']:'';
                $list[$k]['city']=$v['city']?$v['city']:'';
                $list[$k]['created_at']=$v['created_at']?date("Y-m-d H:i",$v['created_at']):'';
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
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;

            return ['code'=>0,'count'=>$count,'data'=>$list?$list:''];
        }
        return $this->render('list');
    }


    /*
     * 批量禁用
     * */
    public function actionDisable(){
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $post=Yii::$app->request->post();
        if(!isset($post['id'])||empty($post['id'])){
            return ['code'=>0,'info'=>'请选择修改数据','data'=>[]];
        }
        $res=User::updateAll(['status'=>'2'],['id'=>$post['id']]);
        return ['code'=>0,'info'=>$res?'操作成功':'操作失败','data'=>[]];
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





}