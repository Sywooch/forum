<?php
namespace frontend\jobs;

use yii\base\BaseObject;

class SiteMsgJob extends BaseObject implements \yii\queue\JobInterface{

    public $obj; //收信人
    public $content; //内容

    public function execute($queue){
        \Yii::$app->db->createCommand()->insert('bbs_site_msg',[
            'user_id' =>$this->obj,
            'content' =>$this->content,
            'created_t'=>time()
        ])->execute();
    }

}

