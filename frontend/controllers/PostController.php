<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use frontend\models\Plate;
use frontend\models\Post;
use frontend\models\Sign;
use frontend\models\Comment;
use frontend\models\CreateForm;
use frontend\models\Collection;
use frontend\models\Star;
use yii\helpers\Url;

class PostController extends Controller{

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($id){
        $PlateModel=new Plate();
        $plates=$PlateModel->getAllSon($id);
        if(empty($plates)){Yii::$app->session->setFlash('warning','无此板块');return $this->goHome();}

        $FatherSonArr=$PlateModel->getFatherSon($plates);
        $far_plate=$FatherSonArr['far_plate'];
        $sid=$FatherSonArr['sid'];
        $bzs=$FatherSonArr['bz_id'];

        $gets=Yii::$app->request->get();
        $PostModel=new Post();
        $PostsParams=$PostModel->getPlatePosts($gets,$sid);

        $platess=$PlateModel->getRecommend();

        $SignModel=new Sign();
        $sign_c=$SignModel->getTodaySign();
        return $this->render('index',['plates'=>$plates,'posts'=>$PostsParams['posts'],'platess'=>$platess,'sign_c'=>$sign_c,'bzs'=>!empty($bzs)?$bzs:'无','far_plate'=>isset($far_plate)?$far_plate:'','id'=>$id,'pagination'=>$PostsParams['pagination'],'o'=>$PostsParams['o'],'f'=>$PostsParams['f'],'s'=>$PostsParams['s'],'t'=>$PostsParams['t']]);
    }

    public function actionDetail(){
        $gets=Yii::$app->request->get();
        if(!isset($gets['id'])||empty($gets['id'])){Yii::$app->session->setFlash('warning','帖子不存在!');return $this->redirect(Url::home());}

        $PostModel=new Post();
        $post=$PostModel->getDetail($gets);

        if(empty($post)){Yii::$app->session->setFlash('warning','帖子不存在!');return $this->redirect(Url::home());}

        $LoginUserId=Yii::$app->user->id;

        $PostModel->updateView($gets['id']);

        $is_coll=0;
        if($LoginUserId){
            $CollectionModel=new Collection();
            $is_coll=$CollectionModel->getUserPostCollection($LoginUserId,$gets['id']);
        }
        $is_star=0;
        if($LoginUserId){
            $StarModel=new Star();
            $is_star=$StarModel->getUserPostStar($LoginUserId,$gets['id']);
        }

        $z_count=$PostModel->getUserPostCount($LoginUserId);

        $CommentModel=new Comment();
        $CommentResult=$CommentModel->getPostComment($gets);

        $PlateModel=new Plate();
        $platess=$PlateModel->getRecommend();

        $SignModel=new Sign();
        $sign_c=$SignModel->getTodaySign();
        return $this->render('detail',['post'=>$post,'z_count'=>$z_count,'comments'=>$CommentResult['comments'],'plates'=>$platess,'sign_c'=>$sign_c,'pagination'=>$CommentResult['pagination'],'id'=>$gets['id'],'author'=>isset($gets['author'])&&is_numeric($gets['author'])?$gets['author']:'','is_coll'=>$is_coll,'is_star'=>$is_star,'comm_count'=>$CommentResult['comm_count']]);
    }

    public function actionCreate(){
        $PlateModel=new Plate();
        $plates=$PlateModel->getAll();
        $models=new CreateForm();
        if($models->load(Yii::$app->request->post())&&$models->createPost()){
            Yii::$app->session->setFlash('success','发表成功!');
            return $this->redirect(Url::home());
        }
        return $this->render('create',['model'=>$models,'plates'=>$plates]);
    }
}