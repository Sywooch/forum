<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use common\models\User;
use frontend\models\Comment;

class Report extends ActiveRecord{

    public static function tableName(){
        return '{{%report}}';
    }

    /**
     * 举报和用户一对一
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }

    /**
     * 举报和评论一对一
     */
    public  function getComment(){
        return $this->hasOne(Comment::className(),['id'=>'comm_id']);
    }


}