<?php
namespace frontend\controllers;

use yii\web\Controller;
use common\models\User;
use frontend\models\Prive;

class PrivateController extends Controller{

    public function actionSend(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //判断是否登陆
        $id=\Yii::$app->user->identity;
        if(!$id){
            return ['code'=>0,'msg'=>'请登陆后操作!','data'=>''];
        }
        $data=\Yii::$app->request->post();
        if(!isset($data['id'])||!isset($data['content'])){
            return ['code'=>0,'msg'=>'请填写私信内容!','data'=>''];
        }
        if(!is_numeric($data['id'])||$data['id']<=0){
            return ['code'=>0,'msg'=>'您对空气私信吧!','data'=>''];
        }
        if(strpos($data['content'],'-')!==false){
            return ['code'=>0,'msg'=>'私信内容不得含有特殊字符!','data'=>''];
        }
        if(strpos($data['content'],'<script>')!==false){
            return ['code'=>0,'msg'=>'私信内容不得含有特殊字符!','data'=>''];
        }
        if(strpos($data['content'],'</script>')!==false){
            return ['code'=>0,'msg'=>'私信内容不得含有特殊字符!','data'=>''];
        }
        if(strpos($data['content'],'%')!==false){
            return ['code'=>0,'msg'=>'私信内容不得含有特殊字符!','data'=>''];
        }
        if(strlen($data['content'])>90){
            return ['code'=>0,'msg'=>'私信内容不得超过30字!','data'=>''];
        }
        $binf=User::find()->where(['id'=>$data['id']])->scalar();
        if(empty($binf)){
            return ['code'=>0,'msg'=>'发送人不存在!','data'=>''];
        }
        if(\Yii::$app->user->id==$data['id']){
            return ['code'=>0,'msg'=>'不能给自己发送私信!','data'=>''];
        }
        $last=Prive::find()->select('created_t')->where(['and',['users_id'=>45],['user_id'=>46]])->orderBy(['id'=>SORT_DESC])->scalar();
        if(!empty($last)){
            if(time()-$last<60){
                return ['code'=>0,'msg'=>'不要过于频发的发私信!','data'=>''];
            }
        }
        $priv=new Prive();
        $priv->users_id=\Yii::$app->user->id;
        $priv->user_id=$data['id'];
        $priv->content=$data['content'];
        $priv->created_t=time();
        $res=$priv->save();
        return ['code'=>$res?1:0,'msg'=>$res?'发送成功':'发送失败','data'=>$data['id']];
    }

}