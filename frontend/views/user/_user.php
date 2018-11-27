<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<dl class="uk-description-list uk-description-list-divider">
    <dt><h5>个人资料</h5></dt>
    <dd class="uk-text-small">
        <p>用户组： <?= $user_info['groups']?></p>
        <p>城市： <?php echo empty($user_info['city'])?'暂无':$user_info['city'];  ?></p>
        <p>性别： <?php echo empty($user_info['sex'])?'暂无':$user_info['sex'];  ?></p>
        <p>个人签名： <?php echo empty($user_info['intro'])?'暂无':Html::encode($user_info['intro']);  ?></p>
    </dd>
    <dt><h5>论坛荣耀</h5></dt>
    <dd class="uk-text-small">还没有获得论坛成就哦，继续努力吧!</dd>
    <dt><h5>活动概况</h5></dt>
    <dd class="uk-text-small">
        <div class="uk-text-small uk-column-1-3 uk-column-divider uk-text-center">
            <p><b>主题</b> <?= $counts ?></p>
            <p><b>回复</b> <?= $countss ?></p>
            <p><b>经验</b> <?= $user_info['experience'] ?></p>
            <p><b>积分</b> <?= $user_info['integral'] ?></p>
            <p><b>最后访问时间</b> <?php echo $user_info['update_at']?date("Y-m-d H:i",$user_info['update_at']):'暂未登陆' ?></p>
            <p><b>注册时间</b> <?= date("Y-m-d H:i",$user_info['created_at']) ?></p>
        </div>
    </dd>
</dl>