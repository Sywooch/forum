<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<p>共<b><?= $counts ?></b>条站内信，其中未读信息<b><?= $post_v ?></b>条</p>
<ul class="uk-list">
    <?php foreach($posts as $post){?>
    <li>
        <div class="uk-flex uk-text-small">
            <div class="uk-width-1-2"><span class="uk-text-primary"><?= $post['content'] ?></span></div>
            <div class="uk-width-1-2 uk-text-right"><?= date("Y-m-d H:i:s",$post['created_t']) ?></div>
        </div>
    </li>
    <hr class="uk-margin-small"/>
    <?php } ?>
    <?= LinkPager::widget(['pagination' => $pagination,'options' => ['class' => 'uk-pagination uk-flex-center'],'prevPageCssClass'=>'uk-pagination-previous','disabledPageCssClass'=>'uk-disabled','activePageCssClass'=>'uk-active','nextPageCssClass'=>'uk-pagination-next','prevPageLabel'=>'<span uk-pagination-previous></span>','nextPageLabel'=>'<span uk-pagination-next></span>']) ?>
</ul>