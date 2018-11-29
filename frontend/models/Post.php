<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\Pagination;
use common\models\User;
use yii\helpers\ArrayHelper;
use frontend\jobs\UpadtePostJob;
use frontend\models\Plate;

class Post extends ActiveRecord{

    public static function tableName(){
        return '{{%posts}}';
    }

    public function getAll($gets){
        $query=self::find();

        $where=[];
        $o=ArrayHelper::getValue($gets, 'o','');
        if(empty($o)){$OrderBy=['id'=>SORT_DESC];}
        if($o&&in_array($o,array(1,2))){
            $pag_param['params']['o']=$o;
            switch($o){
                case '1':
                    $OrderBy['view']=SORT_DESC;
                    break;
                case '2':
                    $OrderBy['comments']=SORT_DESC;
                    break;
            }
        }
        $f=ArrayHelper::getValue($gets, 'f','');
        if($f&&in_array($f,array(1,2))){
            $pag_param['params']['f']=$f;
            $where=$f=='1'?['is_hot'=>1]:['essence'=>1];
        }

        $counts=$where?self::find()->filterWhere($where)->cache(5)->count():Yii::$app->redis->get('post_counts');
        $pag_param=[
            'defaultPageSize' =>20,
            'totalCount' =>$counts,
        ];
        $pagination = new Pagination($pag_param);

        $posts=$query->select(['id','user_id','plate_id','title','view','comments','essence','is_hot','create_at'])->with(['user','plate'])->filterWhere($where)->offset($pagination->offset)->limit($pagination->limit)->orderBy($OrderBy)->cache(5)->all();

        return ['posts'=>$posts,'pagination'=>$pagination,'o'=>$o,'f'=>$f];
    }

    public function getDetail($gets){
        $info=static::find()->select(['user_id','plate_id','title','content','file','comments','essence','is_hot','create_at'])->with('plate','user')->filterWhere(['id'=>$gets['id']])->cache(600)->one();
        if(!empty($info)){
            $info->view=$this->getView($gets['id']);
            $info->collection=$this->getCollection($gets['id']);
            $info->star=$this->getStar($gets['id']);
        }
        return $info;
    }

    public function updateView($id){
        $redis=Yii::$app->redis;
        $key='view_post_'.$id;
        $redis->incr($key);
        Yii::$app->queue->push(new UpadtePostJob([
            'id'=>$id,
            'type'=>'view',
            'direction'=>1
        ]));
    }

    public function updateViews($id){
        $PostModel=static::findOne($id);
        $PostModel->view=($PostModel->view)+1;
        $PostModel->save();
    }

    public function updateCollection($id,$direction){
        $redis=Yii::$app->redis;
        $key='collection_post_'.$id;

        $direction==1?$redis->incr($key):$redis->decr($key);

        Yii::$app->queue->push(new UpadtePostJob([
            'id'=>$id,
            'type'=>'collection',
            'direction'=>$direction
        ]));
    }

    public function updateCollections($id,$direction){
        $PostModel=static::findOne($id);
        $PostModel->collection=$direction==1?($PostModel->collection)+1:($PostModel->collection)-1;
        $PostModel->save();
    }

    public function updateStar($id,$direction){
        $redis=Yii::$app->redis;
        $key='star_post_'.$id;

        $direction==1?$redis->incr($key):$redis->decr($key);

        Yii::$app->queue->push(new UpadtePostJob([
            'id'=>$id,
            'type'=>'star',
            'direction'=>$direction
        ]));
    }

    public function updateStars($id,$direction){
        $PostModel=static::findOne($id);
        $PostModel->star=$direction==1?($PostModel->star)+1:($PostModel->star)-1;
        $PostModel->save();
    }

    public function getPostIsExist($postid){
        return static::find()->select('tos,user_id,notices')->where(['id'=>$postid])->asArray()->one();
    }

    public function getUserPostCount($uid){
        return static::find()->filterWhere(['user_id'=>$uid])->count();
    }

    public function getTo($where){
        return static::find()->select('id,user_id,tos')->where($where)->one();
    }

    public function getView($id){
        $redis=Yii::$app->redis;
        $key='view_post_'.$id;
        if(!$redis->exists($key)){
            $redis->set($key,0);
        }
        return $redis->get($key);
    }

    public function getCollection($id){
        $redis=Yii::$app->redis;
        $key='collection_post_'.$id;
        if(!$redis->exists($key)){
            $redis->set($key,0);
        }
        return $redis->get($key);
    }

    public function getStar($id){
        $redis=Yii::$app->redis;
        $key='star_post_'.$id;
        if(!$redis->exists($key)){
            $redis->set($key,0);
        }
        return $redis->get($key);
    }

    /**
     * 发帖相关
     * @param $data
     */
    public function sendPosts($data){
        $PostModel=new self();
        $PostModel->user_id=$data['uid'];
        $PostModel->plate_id=$data['plate_id'];
        $PostModel->title=$data['title'];
        $PostModel->content=$data['content'];
        $PostModel->notices=$data['notices'];
        $PostModel->create_at=$data['create_at'];
        $res=$PostModel->save();
        if($res){
            $PostsModel=new Posts();
            $PostsModel->primaryKey=$PostModel->id;
            $PostsModel->id=$PostModel->id;
            $PostsModel->user_id=$data['uid'];
            $PostsModel->plate_id=$data['plate_id'];
            $PostsModel->title=$data['title'];
            $PostsModel->content=$data['content'];
            $PostsModel->file='';
            $PostsModel->view=0;
            $PostsModel->comments=0;
            $PostsModel->collection=0;
            $PostsModel->star=0;
            $PostsModel->essence=0;
            $PostsModel->is_hot=0;
            $PostsModel->tos=0;
            $PostsModel->authors=1;
            $PostsModel->notices=$data['notices'];
            $PostsModel->create_at=$data['create_at'];
            $PostsModel->save();

            $UserModel=new User();
            $UserModel->addExperience($data['id'],$data['score']);

            $PlateModel=new Plate();
            $PlateModel->updatePlatePostNum($data['pid']);

            $UserPostModel=new UserPost();
            $UserPostModel->updateUserPosts($data['uid']);

            $redis=Yii::$app->redis;
            $redis->sadd('send_post_id',$PostModel->id);
            $redis->exists('post_counts')?$redis->incr('post_counts'):$redis->set('post_counts',0);

            $redis->set('view_post_'.$PostModel->id,0);
            $redis->set('collection_post_'.$PostModel->id,0);
            $redis->set('star_post_'.$PostModel->id,0);
        }
    }

    public function postFrequency($user){
        $FrequencyModel=new Frequency();
        $FrequencyConfig=$FrequencyModel->getPostsFrequency($user->level);
        $StartTime=time()-($FrequencyConfig['minute']*60);
        $PostCounts=static::find()->where(['and',['user_id'=>$user->id],['>=','create_at',$StartTime]])->count();
        if($PostCounts>=$FrequencyConfig['nums']){return '您所在等级'.$FrequencyConfig['minute'].'分钟内，仅能发帖'.$FrequencyConfig['nums'].'次';}
        return true;
    }

    /**
     * 帖子和用户一对一
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id'])->select('id,email,username,avatar,intro,integral,experience')->cache(30);
    }

    /**
     * 帖子和板块一对一
     */
    public function getPlate(){
        return $this->hasOne(Plate::className(),['id'=>'plate_id'])->select('id,fid,name')->cache(30);
    }

    /**
     * 帖子和评论一对多
     */
    public function getComments(){
        return $this->hasMany(Comment::className(),['post_id'=>'id']);
    }

    /**
     * 帖子和收藏一对多
     */

    public function getCollections(){
        return $this->hasMany(Collection::className(),['post_id'=>'id']);
    }


    /**
     * 帖子和点赞一对多
     */

    public function getStars(){
        return $this->hasMany(Star::className(),['post_id'=>'id']);
    }

    public function getPlatePosts($gets,$sid,$fid){
        $query=static::find();
        $where=isset($gets['s'])&&!empty($gets['s'])?['and']:['and',['plate_id'=>$sid]];
        $o=ArrayHelper::getValue($gets, 'o','');
        if(empty($o)){$order=['id'=>SORT_DESC];}
        if(isset($gets['o'])&&in_array($gets['o'],[1,2])){
            $o=$gets['o'];
            $pag_param['params']['o']=$gets['o'];
            switch($gets['o']){
                case '1':
                    $order['view']=SORT_DESC;
                    break;
                case '2':
                    $order['comments']=SORT_DESC;
                    break;
            }
        }
        $f='';
        if(isset($gets['f'])&&in_array($gets['f'],[1,2])){
            $f=$gets['f'];
            $pag_param['params']['f']=$gets['f'];
            switch($gets['f']){
                case '1':
                    $where[]=['is_hot'=>1];
                    break;
                case '2':
                    $where[]=['essence'=>1];
                    break;
            }
        }
        $t='';
        if(isset($gets['t'])&&in_array($gets['t'],[1,2,3,4,5])){
            $t=$gets['t'];
            $pag_param['params']['t']=$gets['t'];
            switch($gets['t']){
                case '1':
                    $where[]=['>=','create_at',strtotime("-1 day")];
                    break;
                case '2':
                    $where[]=['>=','create_at',strtotime("-2 day")];
                    break;
                case '3':
                    $where[]=['>=','create_at',strtotime("-7 day")];
                    break;
                case '4':
                    $where[]=['>=','create_at',strtotime("-1 month")];
                    break;
                case '5':
                    $where[]=['>=','create_at',strtotime("-3 month")];
                    break;
            }
        }
        $s='';
        if(isset($gets['s'])&&is_numeric($gets['s'])){
            $s=$gets['s'];
            $pag_param['params']['s']=$gets['s'];
            $where[]=['plate_id'=>$gets['s']];
        }
        if(!isset($gets['f'])||empty($gets['f'])){
            $plateModel=new Plate();
            $count=$plateModel->getPlateTotals(isset($gets['s'])&&is_numeric($gets['s'])?$gets['s']:$fid);
        }else{
            $count=$query->filterWhere($where)->count();
        }
        $pag_param=[
            'defaultPageSize' =>20,
            'totalCount' =>$count,
        ];
        $pagination = new Pagination($pag_param);

        $posts = $query->select('id,user_id,plate_id,title,view,comments,essence,is_hot,create_at')->with(['user','plate'])->filterWhere($where)->orderBy($order)->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();

        return ['posts'=>$posts,'pagination'=>$pagination,'o'=>$o,'f'=>$f,'s'=>$s,'t'=>$t];
    }

}