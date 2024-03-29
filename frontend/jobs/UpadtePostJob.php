<?php
namespace frontend\jobs;

use yii\base\BaseObject;
use frontend\models\Post;

class UpadtePostJob extends BaseObject implements \yii\queue\JobInterface{

    public $id;
    public $type;
    public $direction;

    public function execute($queue){
        $postModel=new Post();
        switch($this->type){
            case 'view':
                $postModel->updateViews($this->id);
                break;
            case 'collection':
                $postModel->updateCollections($this->id,$this->direction);
                break;
            case 'star':
                $postModel->updateStars($this->id,$this->direction);
                break;
        }
    }

}