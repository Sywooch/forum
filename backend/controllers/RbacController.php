<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;


class RbacController extends Controller{


    /**
     *权限列表
     */
    public function actionlist(){

        return $this->render('list');

    }

    /**
     *添加权限
     */
    public function actionAddpermission(){




    }


    /**
     *删除权限
     */











}