<?php
use yii\helpers\Url;
$identity=Yii::$app->user->identity;
?>
<div class="layui-header">
    <div class="layui-logo">WEBMEN</div>
    <ul class="layui-nav layui-layout-right">
        <li class="layui-nav-item">
            <a href="javascript:;">
                <?= $identity->username ?>
            </a>
        </li>
        <li class="layui-nav-item"><a href="<?= Url::toRoute(['login/logout']) ?>">退出</a></li>
    </ul>
</div>
