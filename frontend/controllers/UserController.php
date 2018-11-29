<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\User;
use frontend\models\Post;
use frontend\models\Comment;
use frontend\models\Site;
use frontend\models\Prive;
use frontend\models\Collection;
use frontend\models\UpdateForm;
use frontend\models\Sign;
use frontend\models\Level;
use yii\helpers\Url;

class UserController extends Controller{

    public function actionIndex($id,$t='user'){
        $all_params=array();
        $all_params['id']=$id;
        $all_params['t']=$t;

        $user_info=User::find()->select('id,view,city,email,avatar,username,intro,groups,level,experience,integral,update_at,created_at')->filterWhere(['id'=>$id])->cache(10)->one();
        if(empty($user_info)){Yii::$app->session->setFlash('warning','无此用户');return $this->goBack();}

        $groups=new User();
        $user_info['groups']=$groups->role($user_info['groups']);

        $LevelModel=new Level();
        $LevelInfo=$LevelModel->getAppellation($user_info['level']);
        $all_params['sign']=Sign::find()->filterWhere(['user_id'=>$id])->cache(10)->count();

        //发帖信息
        $all_params['user_info']=$user_info;
        $all_params['appellation']=$LevelInfo['appellation'];

        switch($t){
            case 'user':
                if(Yii::$app->user->identity!=$id){$user_info->updateCounters(['view'=>1]);}
                $all_params['counts']=Post::find()->filterWhere(['user_id'=>$id])->cache(10)->count();
                $all_params['countss']=Comment::find()->filterWhere(['user_id'=>$id])->cache(10)->count();
            break;
            case 'send':
                $query=Post::find();
                $counts=$query->filterWhere(['user_id'=>$id])->cache(30)->count();
                $pag_param['params']['t']=$t;
                $pag_param['params']['id']=$id;
                $pag_param=[
                    'defaultPageSize' =>20,
                    'totalCount' =>$counts,
                ];
                $pagination = new Pagination($pag_param);
                $posts=$query->alias('a')->select('a.id,a.plate_id,a.title,a.create_at,a.is_hot,a.essence,a.view,a.comments')->joinWith('plate')->filterWhere(['a.user_id'=>$id])->orderBy(['id'=>SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->asArray()->cache(30)->all();
                $all_params['posts']=$posts;
                $all_params['pagination']=$pagination;
                $all_params['counts']=$counts;
            break;
            case 'reply':
                $query=Comment::find();
                $counts=$query->filterWhere(['and','user_id'=>$id,'comm_yobj'=>1])->cache(30)->count();
                $pag_param['params']['t']=$t;
                $pag_param['params']['id']=$id;
                $pag_param=[
                    'defaultPageSize' =>20,
                    'totalCount' =>$counts,
                ];
                $pagination = new Pagination($pag_param);
                $comments=$query->alias('a')->select('a.id,a.post_id,a.com_content')->joinWith('post.plate')->filterWhere(['and','a.user_id'=>$id,'comm_yobj'=>1])->orderBy(['id'=>SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->asArray()->cache(30)->all();

                $all_params['posts']=$comments;
                $all_params['pagination']=$pagination;
                $all_params['counts']=$counts;
                break;
            case 'site':
                if(Yii::$app->user->id!=$id){
                    Yii::$app->session->setFlash('danger','您没有权限访问!');
                    return $this->redirect(Url::toRoute(['user/index','id'=>$id]));
                }
                $query=Site::find();
                $counts=$query->filterWhere(['user_id'=>$id])->count();
                $counts_v=$query->filterWhere(['user_id'=>$id,'is_view'=>0])->cache(30)->count();
                $pag_param['params']['t']=$t;
                $pag_param['params']['id']=$id;
                $pag_param=[
                    'defaultPageSize' =>20,
                    'totalCount' =>$counts,
                ];
                $pagination = new Pagination($pag_param);
                $sites=$query->select('id,user_id,content,is_view,created_t')->filterWhere(['user_id'=>$id])->offset($pagination->offset)->limit($pagination->limit)->asArray()->cache(30)->all();
                $view_list=array();
                foreach($sites as $v){
                    $view_list[]=$v['id'];
                }
                Site::updateAll(['is_view'=>1],['id'=>$view_list]);
                $all_params['posts']=$sites;
                $all_params['pagination']=$pagination;
                $all_params['counts']=$counts;
                $all_params['counts_v']=$counts_v;
                break;
            case 'private':
                if(Yii::$app->user->id!=$id){
                    Yii::$app->session->setFlash('danger','您没有权限访问!');
                    return $this->redirect(Url::toRoute(['user/index','id'=>$id]));
                }
                $query=Prive::find();
                $counts=$query->filterWhere(['user_id'=>$id])->cache(30)->count();
                $counts_v=$query->filterWhere(['user_id'=>$id,'is_view'=>0])->cache(30)->count();
                $pag_param['params']['t']=$t;
                $pag_param['params']['id']=$id;
                $pag_param=[
                    'defaultPageSize' =>3,
                    'totalCount' =>$counts,
                ];
                $pagination = new Pagination($pag_param);
                $sites=$query->alias('a')->select('a.id,a.users_id,a.content,a.is_view,a.created_t')->joinWith('user')->filterWhere(['a.user_id'=>$id])->offset($pagination->offset)->limit($pagination->limit)->asArray()->cache(30)->all();
                if(!empty($sites)){
                    foreach($sites as $v){
                        $view_list[]=$v['id'];
                    }
                    Prive::updateAll(['is_view'=>1],['id'=>$view_list]);
                }
                $all_params['posts']=$sites;
                $all_params['pagination']=$pagination;
                $all_params['counts']=$counts;
                $all_params['counts_v']=$counts_v;
                break;
            case 'collection':
                if(Yii::$app->user->id!=$id){
                    Yii::$app->session->setFlash('danger','您没有权限访问!');
                    return $this->redirect(Url::toRoute(['user/index','id'=>$id]));
                }
                $query=Collection::find();
                $counts=$query->filterWhere(['user_id'=>$id])->cache(30)->count();
                $pag_param['params']['t']=$t;
                $pag_param['params']['id']=$id;
                $pag_param=[
                    'defaultPageSize' =>20,
                    'totalCount' =>$counts,
                ];
                $pagination = new Pagination($pag_param);
                $collections=$query->alias('a')->select('a.id,a.user_id,a.post_id,a.created_t,bbs_posts.id,bbs_posts.title,bbs_posts.view,bbs_posts.comments,bbs_posts.essence,bbs_posts.is_hot,bbs_posts.create_at')->joinWith('post.plate')->filterWhere(['a.user_id'=>$id])->offset($pagination->offset)->limit($pagination->limit)->asArray()->cache(30)->all();
                $all_params['posts']=$collections;
                $all_params['pagination']=$pagination;
                $all_params['counts']=$counts;
                break;
            case 'set':
                if(Yii::$app->user->id!=$id){
                    Yii::$app->session->setFlash('danger','您没有权限访问!');
                    return $this->redirect(Url::toRoute(['user/index','id'=>$id]));
                }
                $user_info=User::find()->select('id,email,username,avatar,sex,city,intro')->filterWhere(['id'=>$id])->asArray()->one();
                $all_params['posts']=$user_info;
                break;
            case 'pass_reset':
                $model=new UpdateForm(['scenario'=>'pass']);
                if($model->load(\Yii::$app->request->post())&&$model->save(\Yii::$app->user->id)){
                    Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(Url::toRoute(['user/index','id'=>$id]));
                }
                $all_params['model']=$model;
               break;

        }
        return $this->render('index',$all_params);
    }

    public function actionUpdate($id){
        $model=new UpdateForm();
        if($model->load(\Yii::$app->request->post())&&$model->save($id)){
            Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(Url::toRoute(['user/index','id'=>$id,'t'=>'set']));
        }
        return $this->render('index',['id'=>$id,'type'=>'set','model'=>$model]);
    }
}