<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Permission extends ActiveRecord{

    public static function tableName(){
        return '{{%auth_item}}';
    }

    public function getTree($role){
        $params=static::find()->select('id,fid,name,description')->filterWhere(['type'=>2])->limit(1000)->offset(0)->asArray()->all();
        $data=array('trees'=>array());
        $UserPermission=ItemChild::find()->select('child')->where(['parent'=>$role])->limit(1000)->offset(0)->asArray()->column();
        if(!empty($params)){
            $lists=array();
            foreach($params as $k=>$v){
                if($v['fid']==0){
                    $lists[$k]=array('name'=>$v['description'],'value'=>$v['name'],'checked'=>in_array($v['name'],$UserPermission)?true:false);
                    foreach($params as $vs){
                        if($vs['fid']==$v['id']){
                            $lists[$k]['list'][]=array('name'=>$vs['description'],'value'=>$vs['name'],'checked'=>in_array($vs['name'],$UserPermission)?true:false);
                        }
                    }
                }
            }
            $data['trees']=$lists;
        }
        $data['trees']=array_values($data['trees']);
        return $data;
    }

}