<?php
namespace frontend\jobs;

use yii\base\BaseObject;
use frontend\models\Post;

class SendPostJob extends BaseObject implements \yii\queue\JobInterface{

    public $score;
    public $id;
    public $pid;
    public $data=array();

    public function execute($queue){
        $PostModel=new Post();
        $PostModel->sendPosts([
            'score'=>$this->score,
            'id'=>$this->id,
            'pid'=>$this->pid,
            'uid'=>$this->data['uid'],
            'plate_id'=>$this->data['plate_id'],
            'title'=>$this->data['title'],
            'content'=>$this->data['content'],
            'notices'=>$this->data['notices'],
            'create_at'=>$this->data['create_at'],
        ]);
    }
}