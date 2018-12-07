<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Post;
use frontend\models\Plate;
use frontend\models\Sign;

class IndexController extends Controller{

    /**
     * 首页
     * @return mixed
     */
    public function actionIndex(){
        $GetsParams=Yii::$app->request->get();

        $PostModel=new Post();
        $PostParams=$PostModel->getAll($GetsParams);

        $PlateModel=new Plate();
        $plates=$PlateModel->getRecommend();

        $SignModel=new Sign();
        $sign_c=$SignModel->getTodaySign();
        return $this->render('index',['posts'=>$PostParams['posts'],'plates'=>$plates,'sign_c'=>$sign_c,'pagination'=>$PostParams['pagination'],'o'=>$PostParams['o'],'f'=>$PostParams['f']]);
    }

    public function posturl($url,$data){
        $data  = json_encode($data);
        $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

/*for($i=220;$i<230;++$i){
$NowPage=$i;
$PageSize=10000;
$offset=($NowPage-1)*$PageSize;
$query=(new Query())->select(['id','user_id','plate_id','title','content','file','view','comments','collection','star','essence','is_hot','tos','authors','notices','create_at'])
->from('bbs_posts')->limit(10000)->offset($offset)->all();
foreach($query as $posts){
$PostModel=new Post();
$PostModel->primaryKey=$posts['id'];
$PostModel->id=$posts['id'];
$PostModel->user_id=$posts['user_id'];
$PostModel->plate_id=$posts['plate_id'];
$PostModel->title=$posts['title'];
$PostModel->content=$posts['content'];
$PostModel->file=$posts['file'];
$PostModel->comments=$posts['comments'];
$PostModel->collection=$posts['collection'];
$PostModel->star=$posts['star'];
$PostModel->essence=$posts['essence'];
$PostModel->is_hot=$posts['is_hot'];
$PostModel->tos=$posts['tos'];
$PostModel->authors=$posts['authors'];
$PostModel->notices=$posts['notices'];
$PostModel->create_at=$posts['create_at'];
$PostModel->save();
unset($PostModel);
}
sleep(1);
}*/
}