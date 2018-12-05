<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class HomeController extends Controller{

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex(){
        return $this->render('index');
    }

    /*public function int(){
        $auth=Yii::$app->authManager;

        $userList=$auth->createPermission('user/list');
        $userList->description='用户列表';
        $auth->add($userList);

        $userDisable=$auth->createPermission('user/disable');
        $userDisable->description='禁用用户';
        $auth->add($userDisable);

        $postList=$auth->createPermission('post/list');
        $postList->description='帖子列表';
        $auth->add($postList);

        $postHot=$auth->createPermission('post/hot');
        $postHot->description='设为热帖';
        $auth->add($postHot);

        $postEssence=$auth->createPermission('post/essence');
        $postEssence->description='设为精贴';
        $auth->add($postEssence);

        $postEdit=$auth->createPermission('post/edit');
        $postEdit->description='编辑帖子';
        $auth->add($postEdit);

        $postDelete=$auth->createPermission('post/delete');
        $postDelete->description='删除帖子';
        $auth->add($postDelete);

        $plateList=$auth->createPermission('plate/list');
        $plateList->description='版区列表';
        $auth->add($plateList);

        $plateCreate=$auth->createPermission('plate/create');
        $plateCreate->description='新增版区';
        $auth->add($plateCreate);

        $plateEdit=$auth->createPermission('plate/update');
        $plateEdit->description='编辑版区';
        $auth->add($plateEdit);

        $plateClose=$auth->createPermission('plate/close');
        $plateClose->description='关闭版区';
        $auth->add($plateClose);

        $menuList=$auth->createPermission('menu/list');
        $menuList->description='菜单列表';
        $auth->add($menuList);

        $menuCreate=$auth->createPermission('menu/create');
        $menuCreate->description='新增菜单';
        $auth->add($menuCreate);

        $menuEdit=$auth->createPermission('menu/update');
        $menuEdit->description='编辑菜单';
        $auth->add($menuEdit);

        $menuDelete=$auth->createPermission('menu/delete');
        $menuDelete->description='删除菜单';
        $auth->add($menuDelete);

        $roleList=$auth->createPermission('role/list');
        $roleList->description='角色列表';
        $auth->add($roleList);

        $roleCreate=$auth->createPermission('role/create');
        $roleCreate->description='新增角色';
        $auth->add($roleCreate);

        $roleEdit=$auth->createPermission('role/update');
        $roleEdit->description='编辑角色';
        $auth->add($roleEdit);

        $roleDelete=$auth->createPermission('role/delete');
        $roleDelete->description='删除角色';
        $auth->add($roleDelete);

        $permissionList=$auth->createPermission('permission/list');
        $permissionList->description='权限列表';
        $auth->add($permissionList);

        $permissionCreate=$auth->createPermission('permission/create');
        $permissionCreate->description='新增权限';
        $auth->add($permissionCreate);

        $permissionEdit=$auth->createPermission('permission/update');
        $permissionEdit->description='编辑权限';
        $auth->add($permissionEdit);

        $permissionDelete=$auth->createPermission('permission/delete');
        $permissionDelete->description='删除权限';
        $auth->add($permissionDelete);

        $adminRole=$auth->createRole('admin');
        $adminRole->description='管理员';
        $auth->add($adminRole);
        $auth->addChild($adminRole,$userList);
        $auth->addChild($adminRole,$userDisable);
        $auth->addChild($adminRole,$postList);
        $auth->addChild($adminRole,$postHot);
        $auth->addChild($adminRole,$postEssence);
        $auth->addChild($adminRole,$postEdit);
        $auth->addChild($adminRole,$postDelete);
        $auth->addChild($adminRole,$plateList);
        $auth->addChild($adminRole,$plateCreate);
        $auth->addChild($adminRole,$plateEdit);
        $auth->addChild($adminRole,$plateClose);
        $auth->addChild($adminRole,$menuList);
        $auth->addChild($adminRole,$menuCreate);
        $auth->addChild($adminRole,$menuEdit);
        $auth->addChild($adminRole,$menuDelete);
        $auth->addChild($adminRole,$roleList);
        $auth->addChild($adminRole,$roleCreate);
        $auth->addChild($adminRole,$roleEdit);
        $auth->addChild($adminRole,$roleDelete);
        $auth->addChild($adminRole,$permissionList);
        $auth->addChild($adminRole,$permissionCreate);
        $auth->addChild($adminRole,$permissionEdit);
        $auth->addChild($adminRole,$permissionDelete);
    }*/

}