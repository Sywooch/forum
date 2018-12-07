<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div >
    <a href="<?= Url::toRoute(['post/create']) ?>" class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom"><span uk-icon="icon: pencil" class="uk-margin-small-right"></span><span class="uk-text-middle">发表新帖</span></a>
    <div class="uk-card uk-card-default uk-width-1-1">
        <div class="uk-card-header uk-padding-small">
            <div class="uk-grid-small uk-flex-middle uk-flex-center" uk-grid>
                <h6>板块推荐</h6>
            </div>
        </div>
        <div class="uk-card-body uk-padding-remove uk-text-center">
            <?php foreach($plates as $plate){ ?>
                <div class="uk-padding-small uk-padding-remove-left uk-padding-remove-right tjbk"><a href="<?= Url::toRoute(['/post','id'=>$plate['fid'],'s'=>$plate['id']]) ?>"><?= Html::encode($plate['name']) ?></a></div>
            <?php } ?>
            <div style="clear:both;"></div>
        </div>
        <div class="uk-card-footer uk-padding-small uk-text-center">
            <a href="<?= Url::toRoute(['/plate']) ?>" class="uk-button uk-button-text">查看更多板块>>></a>
        </div>
    </div>

    <div class="uk-flex uk-margin-top uk-child-width-1-2">
        <div><button class="uk-button uk-button-danger uk-width-1-1 sign"><span uk-icon="icon: calendar" class="uk-margin-small-right"></span>签到</button></div>
        <div class="uk-card uk-card-default uk-card-body uk-padding-remove uk-text-center"><span uk-icon="icon: users" class="uk-margin-small-right uk-margin-small-top"></span><span class="uk-text-middle signpeo" data-sign="<?= $sign_c ?>"><?= $sign_c ?>人</span></div>
    </div>
    <button class="uk-button teal uk-width-1-1 uk-margin-top"><span uk-icon="icon: info" class="uk-margin-small-right"></span><span class="uk-text-middle">版规</span></button>
    <button class="uk-button teal uk-width-1-1 uk-margin-top"><span uk-icon="icon: info" class="uk-margin-small-right"></span><span class="uk-text-middle">新手指南</span></button>
    <button class="uk-button teal uk-width-1-1 uk-margin-top"><span uk-icon="icon: info" class="uk-margin-small-right"></span><span class="uk-text-middle">最新精华</span></button>
</div>