<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use frontend\models\Post;
use frontend\models\Report;
use frontend\models\Site;
use frontend\models\Frequency;
use yii\data\Pagination;
use frontend\jobs\SendCommentJob;
use yii\helpers\Url;

class Comment extends ActiveRecord{

    public static function tableName(){
        return '{{%comments}}';
    }

    /**
     * 评论和用户一对一
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }

    /**
     * 评论和帖子一对一
     */
    public function getPost(){
        return $this->hasOne(Post::className(),['id'=>'post_id'])->select('id,title,plate_id,view,comments,essence,is_hot,create_at');
    }

    /**
     * 评论和举报一对一
     */

    public function getReport(){
        return $this->hasOne(Report::className(),['comm_id'=>'id']);
    }

    public function getOne($id){
        return self::find()->select('user_id,post_id')->with('user')->where(['id'=>$id])->asArray()->one();
    }

    public function getAjaxContent($id,$data,$to,$comm=''){
        $username=$id->username?$id->username:$id->email;
        if($data['t']=='2'){$b_user=$comm['user']['username']?$comm['user']['username']:$comm['user']['email'];}
        $s_str=$data['t']=='1'?'<a href="'.Url::toRoute(["user/index","id"=>$id->id]).'">'.$username.'</a><a href="'.Url::toRoute(["post/detail","id"=>$data["o"],"author"=>$id->id]).'" class="uk-margin-min-left uk-visible@m">只看该作者</a>':'<a href="'.Url::toRoute(["user/index","id"=>$id->id]).'">'.$username.'</a>@'.$b_user.'<a href="'.Url::toRoute(["post/detail","id"=>$data["o"],"author"=>$id->id]).'" class="uk-margin-min-left uk-visible@m">只看该作者</a>';
        $str='<div class="uk-flex uk-margin-small-top">
                <div class="uk-width-auto"><img src="'.$id->avatar.'" width="50" height="50"/></div>
                <div class="uk-width-expand uk-margin-small-left">
                    <div class="uk-flex">
                        <div class="uk-width-auto uk-text-small uk-text-muted">'.$s_str.'<span class="uk-margin-min-left">'.date("Y-m-d H:i:s",time()).'</span></div>
                        <div class="uk-width-expand uk-text-small uk-text-muted uk-text-right">'.($to+1).'楼</div>
                    </div>
                    <div class="uk-margin-min-top">'.$data["c"].'</div>
                </div>
            </div>
            <hr/>';
        return $str;
    }

    public function setCommentWork($data){
        Yii::$app->queue->push(new SendCommentJob([
            'uid'=>$data['uid'],
            'type'=>$data['type'],
            'pid'=>$data['pid'],
            'bobj'=>$data['bobj'],
            'content'=>$data['content'],
            'tos'=>$data['tos'],
            'puid'=>$data['puid'],
            'notices'=>$data['notices'],
        ]));
    }

    public function addComment($data){
        $model=new self();
        $model->user_id=$data['uid'];
        $model->post_id=$data['pid'];
        $model->comm_yobj=$data['type'];
        $model->comm_obj=$data['bobj'];
        $model->com_content=$data['content'];
        $model->to_n=$data['tos']+1;
        $model->create_at=time();
        return $model->save();
    }

    public function commentRelated($data){
        $PostModel=Post::findOne($data['pid']);
        $PostModel->updateCounters(['tos'=>1]);
        if($data['type']==1){
            $UserModel=new User();
            $UserModel->addExperience($data['uid'],Yii::$app->params['commentIntegral']);
            if($data['notices']==1&&$data['uid']!=$data['puid']){
                $SiteMsgModel=new Site();
                $SiteMsgModel->user_id=$data['puid'];
                $comm_url=Url::toRoute(['post/detail','id'=>$data['pid']]);
                $SiteMsgModel->content='恭喜您，您的帖子有新的评论<a class="uk-margin-left" href="'.$comm_url.'">点击查看</a>';
                $SiteMsgModel->created_t=time();
                $SiteMsgModel->save();
            }
        }
    }

    public function getPostComment($gets){
        $PageSize=20;
        $where=['and',['post_id'=>$gets['id']]];
        if(isset($gets['author'])&&is_numeric($gets['author'])){
            $where[]=array('user_id'=>$gets['author']);
        }
        if(isset($gets['to'])&&is_numeric($gets['to'])&&$gets['to']>0){
            if(!isset($_GET['page'])){
                $_GET['page']=ceil($gets['to']/$PageSize);
            }
        }
        $query=static::find();
        $comm_count=$query->filterWhere($where)->count();
        $pag_param=[
            'defaultPageSize' =>$PageSize,
            'totalCount' =>$comm_count ,
        ];
        $pagination = new Pagination($pag_param);
        $comments=$query->select(['id','user_id','post_id','comm_yobj','comm_obj','com_content','to_n','create_at'])->with('user','post')->filterWhere($where)->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
        return ['comm_count'=>$comm_count,'pagination'=>$pagination,'comments'=>$comments];
    }

    public function commentFrequency($user,$PostId){
        $FrequencyModel=new Frequency();
        $FrequencyConfig=$FrequencyModel->getCommentFrequency($user->level);
        $StartTime=time()-($FrequencyConfig['minute']*60);
        $PostCounts=static::find()->where(['and',['=','user_id',$user->id],['>=','create_at',$StartTime],['=','post_id',$PostId]])->count();
        if($PostCounts>=$FrequencyConfig['nums']){return '您所在等级'.$FrequencyConfig['minute'].'分钟内，仅能评论'.$FrequencyConfig['nums'].'次';}
        return true;
    }

}