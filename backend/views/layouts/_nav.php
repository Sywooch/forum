<?php
use yii\helpers\Url;
?>
<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <ul class="layui-nav layui-nav-tree"  lay-filter="test">

            <li class="layui-nav-item"><a href="<?= Url::toRoute(['home/index']) ?>">主页</a></li>

            <li class="layui-nav-item">
                <a class="" href="javascript:;">用户管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?= Url::toRoute('user/list') ?>">用户列表</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;">帖子管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?= Url::toRoute(['post/list']) ?>">帖子列表</a></dd>
                </dl>
            </li>

            <li class="layui-nav-item">
                <a href="javascript:;">版区管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?= Url::toRoute(['plate/list']) ?>">版区列表</a></dd>
                </dl>
            </li>

            <li class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;">权限管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="<?= Url::toRoute(['menu/list']) ?>">菜单列表</a></dd>
                    <dd><a href="<?= Url::toRoute(['permission/list']) ?>">权限列表</a></dd>
                    <dd><a href="<?= Url::toRoute(['role/list']) ?>">角色列表</a></dd>
                    <dd><a href="<?= Url::toRoute(['post/list']) ?>">管理员列表</a></dd>
                </dl>
            </li>
        </ul>
    </div>
</div>
