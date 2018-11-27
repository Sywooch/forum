<?php
namespace frontend\models;

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
    }

    public function sign($uid){
        $obj=new self();
        $obj->user_id=$uid;
        $obj->sign_time=time();
        return $obj->save();
    }



}
