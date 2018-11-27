<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use common\models\User;
use frontend\models\Post;

class Star extends ActiveRecord{

    public static function tableName(){
        return '{{%star}}';
    }

    /**
     * 点赞和用户一对一
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }

    /**
     * 点赞和帖子一对一
     */
    public function getPost(){
        return $this->hasOne(Post::className(),['id'=>'post_id']);
    }

    public function getUserPostStar($uid,$pid){
        return static::find()->where(['and',['user_id'=>$uid],['post_id'=>$pid]])->count();
    }


}