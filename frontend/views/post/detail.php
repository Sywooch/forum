<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title=Html::encode($post['title']).'--BBS论坛';
?>
<div uk-grid>
    <div class="uk-width-1-1@m uk-width-3-4@l">
       <div class="uk-card uk-card-default uk-card-body uk-padding-small" style="min-height:300px;">
            <ul class="uk-breadcrumb">
                <li><a href="<?= Url::toRoute(['index/index']) ?>">首页</a></li>
                <li><a href="<?= Url::toRoute(['plate/index']) ?>">板块</a></li>
                <li><span><?= Html::encode($post['plate']['name']) ?></span></a></li>
            </ul>
            <div class="uk-flex">
                <div class="uk-width-expand">
                    <div><h4><?= Html::encode($post['title']) ?></h4></div>
                    <div class="uk-flex uk-margin-min-top">
                        <div class="uk-width-auto uk-text-small uk-text-muted"><?php if($post['essence']){?> <span class="uk-label uk-label-warning">精贴</span> <?php } ?> <?php if($post['is_hot']){?> <span class="uk-label uk-label-danger uk-margin-min-left">热帖</span><?php } ?><span class="uk-margin-min-left uk-text-middle uk-visible@m"><?= date("Y-m-d H:i",$post['create_at'])?></span><span class="uk-margin-min-left uk-text-middle"><?= $post['plate']['name'] ?></span></div>
                        <div class="uk-width-expand uk-text-small uk-text-muted uk-text-right"></a><span uk-icon="icon: video-camera;ratio: 0.8"></span><?= $post['view'] ?><span class="uk-margin-min-left" uk-icon="icon: commenting;ratio: 0.8"></span><?= $post['comments'] ?></div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="uk-flex uk-flex-center uk-margin-bottom">
                <div class="uk-width-4-5">
                    <?= $post['content'] ?>
                </div>
            </div>
            <div class="uk-flex uk-flex-center uk-margin-bottom uk-position-bottom">
                <div class="uk-width-2-3 uk-text-center">
                    <a uk-icon="heart" class="uk-margin-left star" <?php if($is_star>0){?> style="color:#1e87f0;" onclick="unstar(<?= $id?>)" <?php }else{ ?> onclick="star(<?= $id?>)" <?php }  ?> ></a><span class="uk-text-middle starnum"><?= $post['star'] ?></span>
                    <a uk-icon="star" class="uk-margin-left collection" <?php if($is_coll>0){?> style="color:#1e87f0;" onclick="uncollection(<?= $id?>)" <?php }else{ ?> onclick="collection(<?= $id?>)" <?php }  ?> ></a><span class="uk-text-middle collnum"><?= $post['collection'] ?></span>
                </div>
            </div>
        </div>
        <div class="uk-card uk-card-default uk-card-body uk-padding-small uk-margin-top">

            <div class="uk-flex uk-flex-middle">
                <div class="uk-width-1-3">
                    <span><?= $comm_count ?>条回复</span><?php if($comm_count){if(empty($author)){?>
                        <a href="<?= Url::toRoute(['post/detail','id'=>$id,'author'=>$post['user']['id']]) ?>" class="uk-margin-min-left">只看该作者</a>
                    <?php }else{ ?><a href="<?= Url::toRoute(['post/detail','id'=>$id]) ?>" class="uk-margin-min-left">查看所有</a><?php } } ?>
                </div>
                <?php if($comm_count){ ?>
                <div class="uk-width-2-3 uk-text-right">
                    <form>
                        <fieldset class="uk-fieldset">
                            <div>
                                <input class="uk-input uk-form-width-small uk-form-small" type="text" value="" placeholder="楼层直达" onblur="to(this,<?= $id ?>,<?=$comm_count?>)">
                            </div>
                        </fieldset>
                    </form>
                </div>
                <?php } ?>
            </div>

            <div class="uk-flex uk-margin-small-top">
                <form class="uk-width-1-1">
                    <fieldset class="uk-fieldset">
                        <div class="uk-margin">
                            <textarea class="uk-textarea" id="comment" data="<?= $id ?>" rows="5" placeholder="请填写评论内容（30字内）" style="resize:none;"></textarea>
                        </div>
                        <div class="uk-margin">
                            <button type="button" class="uk-button uk-button-default uk-width-1-1 comment">发表评论</button>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div class="cft">
            <?php foreach($comments as $k=>$comment){ ?>

            <div class="uk-flex uk-margin-small-top">
                <div class="uk-width-auto"><img src="<?= $comment['user']['avatar']?>" width="50" height="50"/></div>
                <div class="uk-width-expand uk-margin-small-left">
                    <div class="uk-flex">
                        <div class="uk-width-auto uk-text-small uk-text-muted"><a href="<?= Url::toRoute(['user/index','id'=>$comment['user']['id']]) ?>"><?php if($comment['user']['username']){echo $comment['user']['username'];}else{echo $comment['user']['email'];} ?></a><a href="<?= Url::toRoute(['post/detail','id'=>$id,'author'=>$comment['user']['id']]) ?>" class="uk-margin-min-left uk-visible@m">只看该作者</a><span class="uk-margin-min-left"><?= date("Y-m-d H:i",$comment['create_at'])?></span></div>
                        <div class="uk-width-expand uk-text-small uk-text-muted uk-text-right"><a id="<?= $comment['to_n'] ?>"></a><?= $comment['to_n'] ?>楼</div>
                    </div>
                    <div class="uk-margin-min-top"><?= $comment['com_content']?></div>
                    <?php if(\Yii::$app->user->id!=$comment['user']['id']){?>
                    <div class="uk-margin-min-top uk-text-right uk-text-small"><a class="report" uk-data="<?= $comment['id'] ?>" <?php if(\Yii::$app->user->id){ ?> uk-toggle="target:#modal-report" <?php }?>  >举报</a><a class="uk-margin-min-left" uk-toggle="target:#toggle-usage-<?= $comment['id'] ?>">回复</a></div>
                    <div id="toggle-usage-<?= $comment['id'] ?>" hidden><input class="uk-input uk-width-2-3" id="reply<?= $comment['id']?>" type="text" placeholder="请输入评论内容"><button class="uk-button uk-button-primary reply" data="<?= $comment['id'] ?>">发表</button></div>
                    <?php } ?>
                </div>
            </div>
            <hr/>
            <?php } ?>
            </div>
            <?= LinkPager::widget(['pagination' => $pagination,'options' => ['class' => 'uk-pagination uk-flex-center'],'prevPageCssClass'=>'uk-pagination-previous','disabledPageCssClass'=>'uk-disabled','activePageCssClass'=>'uk-active','nextPageCssClass'=>'uk-pagination-next','prevPageLabel'=>'<span uk-pagination-previous></span>','nextPageLabel'=>'<span uk-pagination-next></span>']) ?>
         </div>
    </div>
    <div class="uk-visible@m uk-width-1-4@l">
        <div >
            <div class="uk-card uk-card-default uk-margin-bottom">
                <div class="uk-card-header uk-padding-small">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img class="uk-border-circle" width="60" height="60" src="<?= $post['user']['avatar']?>">
                        </div>
                        <div class="uk-width-expand">
                            <a href="#" class="uk-margin-remove-bottom"><?php if($post['user']['username']){ echo Html::encode($post['user']['username']) ;}else{echo $post['user']['email']; } ?></a>
                            <p class="uk-text-meta uk-margin-remove-top"><small><?php if($post['user']['intro']){ echo Html::encode($post['user']['intro']); }else{ echo '这家伙真懒！'; } ?></small></p>
                        </div>
                    </div>
                </div>
                <div class="uk-card-body uk-padding-small uk-flex uk-child-width-1-3 uk-text-center">
                    <div><b><?= $post['user']['integral']?></b><br/>积分</div>
                    <div><b><?= $post['user']['experience']?></b><br/>经验</div>
                    <div><b><?= $z_count ?></b><br/>发帖</div>
                </div>
                <div class="uk-card-footer uk-padding-small uk-text-center">
                    <a href="<?= Url::toRoute(['user/index','id'=>$post['user']['id']]) ?>" class="uk-button uk-button-default">Ta的主页</a>
                    <button class="uk-button uk-button-default private" uk-toggle="target:#modal-prive">发私信</button>
                </div>
            </div>

            <?= \Yii::$app->view->renderFile('@app/views/layouts/_right.php',['plates'=>$plates,'sign_c'=>$sign_c]) ?>
        </div>
    </div>
</div>
<div id="modal-report" uk-modal class="uk-flex-top">
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">请选择原因:</h2>
        <form>
            <fieldset class="uk-fieldset">
                <div class="uk-margin">
                    <select class="uk-select" id="repson">
                        <option value="1">色情</option>
                        <option value="3">危险言论</option>
                        <option value="4">广告</option>
                        <option value="2">政治敏感话题</option>
                        <option value="5">其他</option>
                    </select>
                </div>

                <div class="uk-margin">
                    <textarea class="uk-textarea" id="repcon" style="resize:none;" rows="5" placeholder="请填写举报原因(20字内)"></textarea>
                </div>
                <div class="uk-margin">
                    <button type="button" class="uk-button uk-button-primary uk-width-1-1 repsub">提交</button>
                </div>

            </fieldset>
        </form>
    </div>
</div>
<div id="modal-prive" uk-modal class="uk-flex-top">
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <form>
            <fieldset class="uk-fieldset">
                <div class="uk-margin">
                    <textarea class="uk-textarea" id="pricontent" style="resize:none;" rows="5" placeholder="请填写私信内容(20字内)"></textarea>
                </div>
                <div class="uk-margin">
                    <button type="button" class="uk-button uk-button-primary uk-width-1-1 prisub" data="<?= $post['user']['id'] ?>">提交</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
</script>
