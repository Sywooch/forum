<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;

class Sign extends ActiveRecord{

    public static function tableName(){
        return '{{%sign}}';
    }

    /**
     * 签到和用户一对一
     */

    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }

    public function getTodaySign(){
        $redis=Yii::$app->redis;
        $key1='sign'.date("md").'counts';
        return $redis->exists($key1)?$redis->get($key1):0;
    }

    public function getTodayUserSign($id){
        $redis=Yii::$app->redis;
        $key='sign'.date("md").$id;
        return $redis->exists($key)?1:0;
    }

    public function sign($uid){
        $redis=Yii::$app->redis;
        $key='sign'.date("md").$uid;
        $redis->set($key,1);
        $redis->expire($key,86400);
        $key1='sign'.date("md").'counts';
        if($redis->exists($key1)){
            $redis->incr($key1);
        }else{
            $redis->set($key1,1);
            $redis->expire($key1,86400);
        }
        $obj=new self();
        $obj->user_id=$uid;
        $obj->sign_time=time();
        return $obj->save();
    }

    /*public function getTodaySign(){
        $t = time();
        $start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        return static::find()->where(['and',['>=','sign_time',$start_time],['<=','sign_time',$end_time]])->count();
    }

    public function getTodayUserSign($id){
        $t = time();
        $start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        return static::find()->where(['and',['>=','sign_time',$start_time],['<=','sign_time',$end_time],['user_id'=>$id]])->count();
    }*/

}
