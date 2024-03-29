<?php
namespace frontend\models;

use Yii;
use yii\elasticsearch\ActiveRecord;
use yii\data\Pagination;
use common\models\User;
use yii\helpers\ArrayHelper;

class Posts extends ActiveRecord{

    public static function tableName(){
        return '{{%posts}}';
    }

    public function getSearch($gets){
        $query=self::find();
        $where=[];
        $search=ArrayHelper::getValue($gets, 'search','');
        if(!empty($search)){
            //$where=['or',['title'=>$search],['content'=>$search]];
            $where=['title'=>$search];
            $pag_param['params']['search']=$search;
        }
        $counts=$where?$query->filterWhere($where)->count():$query->count();
        if($counts>10000){$counts=10000;}

        $pag_param=[
            'defaultPageSize'=>20,
            'totalCount' =>$counts,
        ];
        $pagination = new Pagination($pag_param);
        $posts=$query->fields(['id','user_id','plate_id','title','view','comments','essence','is_hot','create_at'])->with(['user','plate'])->filterWhere($where)->offset($pagination->offset)->limit($pagination->limit)->orderBy(['id'=>SORT_DESC])->all();
        //print_r($posts);exit;
        return ['posts'=>$posts,'pagination'=>$pagination,'search'=>$search];
    }

    public function getDetail($gets){
        return static::find()->fields(['user_id','plate_id','title','content','file','view','comments','collection','star','essence','is_hot','create_at'])->with('plate','user')->filterWhere(['id'=>$gets['id']])->one();
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

    public function attributes(){
        return ['id','user_id','plate_id','title','content','file','view','comments','collection','star','essence','is_hot','tos','authors','notices','create_at'];
    }

    public  static function type(){
        return 'doc';
    }

    /**
     * @return array This model's mapping
     */
    public static function mapping(){
        return [
            static::type() => [
                'properties' => [
                    'id'=>['type' =>'integer'],
                    'user_id'=> ['type' => 'integer'],
                    'plate_id'=> ['type' => 'integer'],
                    'title' => ['type' => 'string','analyzer'=>'ik_smart','search_analyzer'=>'ik_smart'],
                    'content' => ['type' => 'string'],
                    'file' => ['type' => 'string'],
                    'view' => ['type' => 'byte'],
                    'comments' => ['type' => 'short'],
                    'collection' => ['type' => 'short'],
                    'star' => ['type' => 'short'],
                    'essence' => ['type' => 'byte'],
                    'is_hot' => ['type' => 'byte'],
                    'tos'=>['type'=>'short'],
                    'authors'=>['type'=>'byte'],
                    'notices'=>['type'=>'byte'],
                    'create_at'=> ['type'=>'long'],
                ]
            ],
        ];
    }

    /**
     * Set (update) mappings for this model
     */
    public static function updateMapping(){
        $db = static::getDb();
        $command = $db->createCommand();
        $command->setMapping(static::index(), static::type(), static::mapping());
    }

    /**
     * Create this model's index
     */
    public static function createIndex(){
        $db = static::getDb();
        $command = $db->createCommand();
        $command->createIndex(static::index(), [
            'settings' => [ 'index' => ['refresh_interval' => '1s'] ],
            'mappings' => static::mapping()
        ]);
    }

    /**
     * Delete this model's index
     */
    public static function deleteIndex(){
        $db = static::getDb();
        $command = $db->createCommand();
        $command->deleteIndex(static::index(), static::type());
    }
}