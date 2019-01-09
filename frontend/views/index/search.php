<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title='搜索-BBS论坛';
?>
<div uk-grid>
    <div class="uk-width-1-1@m uk-width-1-1@l uk-margin-small-bottom">
        <div class="uk-card uk-card-default uk-card-body uk-padding-small">
            <?php if(empty($posts)){ ?>

            <div class="uk-flex uk-flex-middle">
                <div class="uk-width-1-3">
                    暂无数据
                </div>

            </div>
            <?php }else{ ?>
            <div class="uk-flex uk-flex-middle">
                    <div class="uk-width-1-1">
                        搜索结果如下：共找到<?= $pagination->totalCount ?> 条符合条件的数据
                    </div>
            </div>
            <?php foreach($posts as $post){ ?>
                <div class="uk-flex uk-margin-medium-top">
                    <div class="uk-width-auto"><img src="<?= $post['user']['avatar'] ?>" width="50" height="50"/></div>
                    <div class="uk-width-expand uk-margin-small-left">
                        <div><a href="<?= Url::toRoute(['post/detail','id'=>$post['id']]) ?>"><?= Html::encode($post['title']) ?></a> <?php if($post['essence']){?><span class="uk-label uk-label-warning">精贴</span> <?php }?> <?php if($post['is_hot']){?><span class="uk-label uk-label-danger uk-margin-min-left">热帖</span> <?php }?></div>
                        <div class="uk-flex uk-margin-min-top">
                            <div class="uk-width-auto uk-text-small uk-text-muted"><a href="<?= Url::toRoute(['user/index','id'=>$post['user']['id']]) ?>"><?php if($post['user']['username']){ echo Html::encode($post['user']['username']); }else{ echo Html::encode($post['user']['email']); } ?></a><span class="uk-margin-min-left"><?= \Yii::$app->formatter->asRelativeTime($post['create_at'])?></span><span class="uk-margin-min-left uk-visible@m"><a href="<?= Url::toRoute(['post/index','id'=>$post['plate']['fid'],'s'=>$post['plate']['id']]) ?>"><?= Html::encode($post['plate']['name']) ?></a></span></div>
                            <div class="uk-width-expand uk-text-small uk-text-muted uk-text-right"><span uk-icon="icon: user;ratio: 0.8"></span><?= $post['view'] ?><span class="uk-margin-min-left" uk-icon="icon:  comments;ratio: 0.8"></span><?= $post['comments'] ?></div>
                        </div>
                    </div>
                </div>
                <hr/>
            <?php } ?>
            <?= LinkPager::widget(['pagination'=>$pagination,'options' => ['class' => 'uk-pagination uk-flex-center'],'prevPageCssClass'=>'uk-pagination-previous','disabledPageCssClass'=>'uk-disabled','activePageCssClass'=>'uk-active','nextPageCssClass'=>'uk-pagination-next','prevPageLabel'=>'<span uk-pagination-previous></span>','nextPageLabel'=>'<span uk-pagination-next></span>']) ?>

            <?php }?>
        </div>
    </div>
</div>
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
</script>

