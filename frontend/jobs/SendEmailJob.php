<?php
namespace frontend\jobs;

use Yii;
use yii\base\BaseObject;

class SendEmailJob extends BaseObject implements \yii\queue\JobInterface{

    public $type;
    public $title;
    public $to;
    public $url;

    public function execute($queue){
        $html=$this->getHtml($this->type);
        if(is_array($html)){
            Yii::$app->mailer->compose(['html'=>$html['html']],$html['params'])
                ->setFrom([Yii::$app->params['fromEmail']=>Yii::$app->params['emailName']])
                ->setTo($this->to)
                ->setSubject($this->title)
                ->send();
        }
    }

    private function getHtml($type){
        $view='';
        switch($type){
            case 'register':
                $view=['html'=>'register-html','params'=>['img'=>Yii::$app->params['registerImage'],'url'=>$this->url]];
                break;
            case 'reset':
                $view=['html'=>'passwordResetToken-html','params'=>['img'=>Yii::$app->params['registerImage'],'url'=>$this->url]];
                break;
        }
        return $view;
    }



}