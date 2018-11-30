<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use frontend\models\Plate;

class PlateController extends Controller{

    public function actionList(){
        if(Yii::$app->request->isAjax||Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            $where=[];
            if(isset($post['name'])&&!empty($post['name'])){
                $where=['LIKE','name','%'.$post['name'].'%'];
            }
            $query=Plate::find();
            $count=$query->filterWhere($where)->count();
            $pagination=new Pagination([
                'defaultPageSize' =>isset($post['limit'])?$post['limit']:10,
                'totalCount' =>$count,
                'params'=>['page'=>isset($post['page'])?$post['page']:1],
            ]);
            $list=$query->select('id,fid,img,name,intro,totals,is_recommend,create_at')->filterWhere($where)->orderBy(['id'=>SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
            $list=$this->conversion($list);
            Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            return ['code'=>0,'count'=>$count,'data'=>$list?$list:[]];
        }
        return $this->render('list',['title'=>'版区管理']);
    }

    private function conversion($list){
        $lists=[];
        if(!empty($list)){
            foreach($list as $k=>$v){
                if($v['fid']==0){
                    $v['is_recommend']=$v['is_recommend']?'推荐':'不推荐';
                    $v['create_at']=$v['create_at']?date("Y-m-d H:i"):'';
                    $lists[]=$v;
                    foreach($list as $ks=>$vs){
                        if($vs['fid']==$v['id']){
                            $vs['is_recommend']=$v['is_recommend']?'推荐':'不推荐';
                            $vs['create_at']=$v['create_at']?date("Y-m-d H:i"):'';
                            $lists[]=$vs;
                        }
                    }
                }
            }
        }
        return $lists;
    }


}