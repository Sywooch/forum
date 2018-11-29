<?php
namespace frontend\jobs;

use yii\base\BaseObject;
use common\models\User;

class SignJob extends BaseObject implements \yii\queue\JobInterface{

    public $val;
    public $uid;

    public function execute($queue){
       $user=new User();
       $user->addIntegral($this->uid,$this->val);
    }
}