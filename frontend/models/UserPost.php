<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class UserPost extends ActiveRecord{

    public static function tableName(){
        return '{{%users_post}}';
    }

    /**
     * 更新某个用户发帖数量
     * @param $uid
     */
    public function updateUserPosts($uid){
        $UserPostModel=static::findOne(['user_id'=>$uid]);
        $UserPostModel->updateCounters(['post_num' =>1]);
    }

    public function getUserPostNum($uid){
        return static::find()->where(['user_id'=>$uid])->count();
    }

}