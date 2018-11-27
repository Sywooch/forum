<?php
namespace frontend\jobs;

use yii\base\BaseObject;
use frontend\models\Comment;

class SendCommentJob extends BaseObject implements \yii\queue\JobInterface{

    public $uid;
    public $type;
    public $pid;
    public $bobj;
    public $content;
    public $tos;
    public $puid;
    public $notices;

    public function execute($queue){
        $CommentModel=new Comment();
        $data=[
            'uid'=>$this->uid,
            'type'=>$this->type,
            'pid'=>$this->pid,
            'bobj'=>$this->bobj,
            'content'=>$this->content,
            'tos'=>$this->tos,
            'puid'=>$this->puid,
            'notices'=>$this->notices,
        ];
        $CommentModel->addComment($data);
        $CommentModel->commentRelated($data);
    }

}