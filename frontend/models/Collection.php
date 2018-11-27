<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use common\models\User;
use frontend\models\Post;

class Collection extends ActiveRecord{

    public static function tableName(){
        return '{{%collection}}';
    }

    /**
     * 收藏和用户一对一
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }

    /**
     * 收藏和帖子一对一
     */
    public function getPost(){
        return $this->hasOne(Post::className(),['id'=>'post_id']);
    }

    public function getUserPostCollection($uid,$pid){
        return static::find()->where(['and',['user_id'=>$uid],['post_id'=>$pid]])->count();
    }

}