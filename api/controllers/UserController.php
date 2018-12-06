<?php
namespace api\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\data\Pagination;
use api\models\User;

class UserController extends Controller{

    public function behaviors(){
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        $behaviors['rateLimiter']['enableRateLimitHeaders'] = false;
        return $behaviors;
    }

   public function actionIndex(){
        $query=User::find();
        $count=$query->count();
        $pagination=new Pagination([
            'defaultPageSize'=>20,
            'totalCount'=>$count,
        ]);
        $list=$query->limit($pagination->limit)->offset($pagination->offset)->all();
        return ['code'=>0,'message'=>'列表如下','data'=>['list'=>$list,'count'=>$count]];
   }

   public function actionView($id){
        if(!is_numeric($id)){return ['code'=>0,'message'=>'请求参数错误','data'=>['info'=>null]];}
        $info=User::findOne($id);
        return ['code'=>0,'message'=>'信息如下','data'=>['info'=>$info]];
   }


}