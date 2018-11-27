<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title='首页-BBS论坛';
?>
<div uk-grid>
    <div class="uk-width-1-1@m uk-width-3-4@l">
        <div class="uk-card uk-card-default uk-card-body uk-padding-small">
            <div class="uk-flex uk-flex-middle uk-background-muted">
                <div class="uk-width-1-3">
                    <select class="uk-select uk-width-2-3@l" name="o" onchange="<?php if(!empty($f)){?> porders(this.value,<?= $f ?>) <?php  }else{?> porders(this.value) <?php  }?>">
                        <option value="0" >默认排序</option>
                        <option value="1" <?php if($o=='1'){ ?> selected <?php  }?> >最多浏览</option>
                        <option value="2" <?php if($o=='2'){ ?> selected <?php  }?> >最多回帖</option>
                    </select>
                </div>
                <div class="uk-width-2-3 uk-text-right"><a href="<?= Url::toRoute(['index/index']) ?>">全部</a>  /  <a href="<?= Url::toRoute(['index/index','f'=>1]) ?>">热门</a>  /  <a href="<?= Url::toRoute(['index/index','f'=>2]) ?>">精华</a> </div>
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
        </div>
    </div>
    <div class="uk-visible@m uk-width-1-4@l">
        <?= \Yii::$app->view->renderFile('@app/views/layouts/_right.php',['plates'=>$plates,'sign_c'=>$sign_c]) ?>
    </div>
</div>
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
</script>

