<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;

class UserController extends Controller{

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['list'],
                        'roles' => ['user/list'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['disable'],
                        'roles' => ['user/disable'],
                    ],
                ],
            ],
        ];
    }

    public function actionList(){
        if(Yii::$app->request->isAjax){
            $get=Yii::$app->request->get();
            $where=['or'];
            if(isset($get['name'])&&!empty($get['name'])){
                $where[]=['LIKE','email',$get['name']];
                $where[]=['LIKE','username',$get['name']];
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

    private function conversion($list){
        if(!empty($list)){
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
        }
        return $list;
    }

}