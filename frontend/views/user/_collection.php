<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<p>共<b><?= $counts ?></b>条收藏</p>
<ul class="uk-list">
    <?php foreach($posts as $post){ ?>
    <li>
        <div><a href="<?= Url::toRoute(['post/detail','id'=>$post['post']['id']]) ?>"><?= $post['post']['title'] ?></a><?php if($post['essence']){?><span class="uk-label uk-label-warning">精贴</span> <?php }?> <?php if($post['is_hot']){?><span class="uk-label uk-label-danger uk-margin-min-left">热帖</span> <?php }?></div>
        <div class="uk-flex">
            <div class="uk-width-1-2"><span><?= $post['post']['plate']['name'] ?></span><span class="uk-margin-small-left"><?= date("Y-m-d H:i:s",$post['post']['create_at'])?></span></div>
            <div class="uk-width-1-2 uk-text-right"><span uk-icon="icon: video-camera;ratio: 0.8"></span><?= $post['post']['view'] ?><span class="uk-margin-min-left" uk-icon="icon: commenting;ratio: 0.8"></span><?= $post['post']['comments'] ?></div>
        </div>
    </li>
    <hr class="uk-margin-small"/>
    <?php } ?>
    <?= LinkPager::widget(['pagination' => $pagination,'options' => ['class' => 'uk-pagination uk-flex-center'],'prevPageCssClass'=>'uk-pagination-previous','disabledPageCssClass'=>'uk-disabled','activePageCssClass'=>'uk-active','nextPageCssClass'=>'uk-pagination-next','prevPageLabel'=>'<span uk-pagination-previous></span>','nextPageLabel'=>'<span uk-pagination-next></span>']) ?>
</ul>