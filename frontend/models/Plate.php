<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;

class Plate extends ActiveRecord{

    public static function tableName(){
        return '{{%plates}}';
    }

    /**
     * 版区和帖子一对多
     */
    public function getPosts(){
        return $this->hasMany(Post::className(),['plate_id'=>'id']);
    }

    /**
     * 板块和版主多对多
     */
    public function getModers(){
        return $this->hasMany(Moder::className(),['plate_id'=>'id'])->select('plate_id,user_id');
    }

    /**
     * 板块和草稿箱一对多
     */
    public function getDraft(){
        return $this->hasMany(Draft::className(),['plate_id'=>'id']);
    }

    public function getRecommend(){
        return static::find()->select('id,fid,name')->where(['is_recommend'=>1])->offset(0)->limit(10)->asArray()->cache(600)->all();
    }

    public function getAll(){
        return static::find()->select('id,name')->where(['>','fid',0])->asArray()->cache(600)->all();
    }

    public function getAllSon($id){
        $result=static::find()->alias('a')->select('a.id,a.fid,a.name,a.img,a.today,a.totals')->with('moders')->filterWhere(['or',['a.id'=>$id],['a.fid'=>$id]])->asArray()->cache(60)->all();
        if(!empty($result)){
            if(count($result)==1){
                $id=$result[0]['fid'];
                $result=static::find()->alias('a')->select('a.id,a.fid,a.name,a.img,a.today,a.totals')->with('moders')->filterWhere(['or',['a.id'=>$id],['a.fid'=>$id]])->asArray()->cache(60)->all();
            }
        }
        return $result;
    }

    public function getPlateTotals($id){
        return static::find()->select('totals')->where(['id'=>$id])->cache(5)->scalar();
    }

    public function getFatherSon($plates){
        $bz_id=[];
        $sid=[];
        foreach($plates as $k=>$v){
            if($v['fid']==0){
                $far_plate=array('id'=>$v['id'],'name'=>$v['name'],'img'=>$v['img'],'today'=>$v['today'],'totals'=>$v['totals']);
                unset($plates[$k]);
            }else{
                $sid[]=$v['id'];
                if(!empty($v['moders'])){
                    $bz_id[]=$v['moders'][0]['user_id'];
                }
            }
        }
        $redis=Yii::$app->redis;
        $key='send_'.date('md').'_posts_'.$far_plate['id'];
        if($redis->exists($key)){
            $far_plate['today']=$redis->get($key);
        }else{
            $far_plate['today']=0;
            $redis->set($key,0);
            $redis->expire($key,86400);
        }
        $gets=Yii::$app->request->get();
        if(isset($gets['s'])&&in_array($gets['s'],$sid)){
            $key1='send_'.date('md').'_posts_'.$gets['s'];
            if($redis->exists($key1)){
                $far_plate['today']=$redis->get($key1);
            }else{
                $far_plate['today']=0;
                $redis->set($key1,0);
                $redis->expire($key1,86400);
            }
        }
        $userModel=new User();
        $bzs=$userModel->getUsernameFields($bz_id);
        return ['far_plate'=>$far_plate,'bz_id'=>$bzs,'sid'=>$sid];
    }

    /**
     * 更新某个版区发帖量和父版区发帖量
     * @param $pid
     */
    public function updatePlatePostNum($pid){
        $plates=static::findOne($pid);
        $plates->updateCounters(['totals' =>1]);
        $redis=Yii::$app->redis;
        $key='send_'.date('md').'_posts_'.$pid;
        if($redis->exists($key)){
            $redis->incr($key);
        }else{
            $redis->set($key,0);
            $redis->expire($key,86400);
        }
        if($plates->fid){
            $plates=static::findOne($plates->fid);
            $plates->updateCounters(['totals' =>1]);
            $key1='send_'.date('md').'_posts_'.$plates->id;
            if($redis->exists($key1)){
                $redis->incr($key1);
            }else{
                $redis->set($key1,0);
                $redis->expire($key1,86400);
            }
        }
    }
}