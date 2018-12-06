<?php
namespace api\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\filters\RateLimitInterface;

class User extends ActiveRecord implements IdentityInterface,RateLimitInterface{

    public static function tableName(){
        return '{{%users}}';
    }

    public function fields(){
        return [
            'id',
            'emails'=>'email',
            'name'=>'username',
            'create'=>function($model){
                return $model->created_at?date('Y-m-d H:i'):'';
            }

        ];
    }

    public static function findIdentity($id){
        return null;
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['access_token' => $token]);
    }

    public function getId(){
        return $this->getPrimaryKey();
    }

    public function getAuthKey(){
        return $this->auth_key;
    }

    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }

    public function getRateLimit($request,$action){
        return [30,60];
    }

    public function loadAllowance($request, $action){
        $cache=Yii::$app->cache;
        $user_id = $this->getId();
        $key='rate_'.$user_id;
        return $cache->get($key);
    }

    public function saveAllowance($request, $action, $allowance, $timestamp){
        $cache=Yii::$app->cache;
        $user_id = $this->getId();
        $key='rate_'.$user_id;
        $cache->set($key,[$allowance,$timestamp]);
    }

}