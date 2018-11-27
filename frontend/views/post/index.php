<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title=$far_plate['name'].'-BBS论坛';
?>
<div uk-grid>
    <div class="uk-width-1-1@m uk-width-3-4@l">
        <div class="uk-card uk-card-default uk-card-body uk-padding-small uk-margin-bottom">
            <div class="uk-flex">
                <div class="uk-width-auto"><img src="<?= $far_plate['img'] ?>" width="80" height="80"/></div>
                <div class="uk-width-expand uk-margin-small-left">
                    <div class="uk-flex">
                        <div class="uk-width-auto"><h4 class="uk-margin-remove-bottom"><?= $far_plate['name'] ?></h4></div>
                        <!--<div class="uk-width-expand uk-text-small uk-text-muted uk-text-right"><a href="javascript:void(0)" class="collect" onclick="collection(<?/*= $id */?>)">收藏</a></div>-->
                    </div>
                    <div class="uk-text-small">
                        <ul class="uk-subnav uk-margin-small-bottom">
                            <li <?php if(empty($s)){ ?> class="uk-active" <?php } ?> ><a href="<?= Url::toRoute(['post/index','id'=>$id,'t'=>$t,'o'=>$o,'f'=>$f]) ?>">全部</a></li>
                            <?php foreach($plates as $plate){ ?>
                                <?php if($plate['fid']!=0) { ?>
                                    <li <?php if($s==$plate['id']){ ?> class="uk-active" <?php } ?> ><a href="<?= Url::toRoute(['post/index','id'=>$id,'s'=>$plate['id'],'t'=>$t,'o'=>$o,'f'=>$f]) ?>"><?=  $plate['name'] ?></a></li>
                                <?php } ?>
                            <?php }  ?>
                        </ul>
                    </div>
                    <div class="uk-text-small uk-text-muted">
                        <span>今日:</span><span class="uk-margin-min-left"><?= $far_plate['today'] ?></span><span class="uk-margin-min-left">帖子:</span><span class="uk-margin-min-left"><?= $far_plate['totals'] ?></span><span class="uk-margin-min-left uk-visible@m">版主:</span><span class="uk-margin-min-left uk-visible@m"><?= $bzs ?></span>
                        <!--<div class="uk-width-auto uk-text-small uk-text-muted"></div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-card uk-card-default uk-card-body uk-padding-small">
            <div class="uk-flex uk-flex-middle uk-background-muted">
                <div class="uk-width-1-3">
                    <select class="uk-select uk-width-2-5@l" name="o" onchange="piorders(this.value,<?= $id ?>,<?= empty($s)?0:$s; ?>,<?= empty($f)?0:$f; ?>)" >
                         <option value="0" >默认排序</option>
                        <option value="1" <?php if($o=='1'){ ?> selected <?php  }?> >最多浏览</option>
                        <option value="2" <?php if($o=='2'){ ?> selected <?php  }?> >最多回帖</option>
                    </select>
                    <select class="uk-select uk-width-2-5@l uk-visible@m" name="t" onchange="pit(this.value,<?= $id ?>,<?= empty($s)?0:$s; ?>,<?= empty($f)?0:$f; ?>)" >
                        <option value="">全部时间</option>
                        <option value="1" <?php if($t=='1'){ ?> selected <?php  }?> >一天</option>
                        <option value="2" <?php if($t=='2'){ ?> selected <?php  }?> >两天</option>
                        <option value="3" <?php if($t=='3'){ ?> selected <?php  }?> >一周</option>
                        <option value="4" <?php if($t=='4'){ ?> selected <?php  }?> >一个月</option>
                        <option value="5" <?php if($t=='5'){ ?> selected <?php  }?> >三个月</option>
                    </select>
                </div>
                <div class="uk-width-2-3 uk-text-right"><a href="<?= Url::toRoute(['post/index','id'=>$id,'s'=>$s]) ?>">全部</a>  /  <a href="<?= Url::toRoute(['post/index','id'=>$id,'f'=>1,'s'=>$s]) ?>">热门</a>  /  <a href="<?= Url::toRoute(['post/index','id'=>$id,'f'=>2,'s'=>$s]) ?>">精华</a> </div>
            </div>

            <?php foreach($posts as $post){ ?>
                <div class="uk-flex uk-margin-medium-top">
                    <div class="uk-width-auto"><img src="<?= $post['user']['avatar']?>" width="50" height="50"/></div>
                    <div class="uk-width-expand uk-margin-small-left">
                        <div><a href="<?= Url::toRoute(['post/detail','id'=>$post['id']]) ?>"><?= Html::encode($post['title']) ?></a> <?php if($post['essence']){?><span class="uk-label uk-label-warning">精贴</span> <?php }?> <?php if($post['is_hot']){?><span class="uk-label uk-label-danger uk-margin-min-left">热帖</span> <?php }?></div>
                        <div class="uk-flex uk-margin-min-top">
                            <div class="uk-width-auto uk-text-small uk-text-muted"><a href="<?= Url::toRoute(['user/index','id'=>$post['user']['id']]) ?>"><?= !empty($post['user']['username'])?$post['user']['username']:$post['user']['email'] ?></a><span class="uk-margin-min-left"><?= \Yii::$app->formatter->asRelativeTime($post['create_at'])?></span><span class="uk-margin-min-left uk-visible@m"><?= Html::encode($post['plate']['name']) ?></span></div>
                            <div class="uk-width-expand uk-text-small uk-text-muted uk-text-right"><span uk-icon="icon: video-camera;ratio: 0.8"></span><?= $post['view'] ?><span class="uk-margin-min-left" uk-icon="icon: commenting;ratio: 0.8"></span><?= $post['comments'] ?></div>
                        </div>
                    </div>
                </div>
                <hr/>
            <?php } ?>
            <?= LinkPager::widget(['pagination' => $pagination,'options' => ['class' => 'uk-pagination uk-flex-center'],'prevPageCssClass'=>'uk-pagination-previous','disabledPageCssClass'=>'uk-disabled','activePageCssClass'=>'uk-active','nextPageCssClass'=>'uk-pagination-next','prevPageLabel'=>'<span uk-pagination-previous></span>','nextPageLabel'=>'<span uk-pagination-next></span>']) ?>
        </div>
    </div>
    <div class="uk-visible@m uk-width-1-4@l">
        <?= \Yii::$app->view->renderFile('@app/views/layouts/_right.php',['plates'=>$platess,'sign_c'=>$sign_c]) ?>
    </div>
</div>
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
</script>