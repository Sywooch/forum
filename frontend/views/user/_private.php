<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<p>共<b><?= $counts ?></b>条站私人消息，其中未读信息<b><?= $post_v ?></b>条</p>
<ul class="uk-list">
    <?php foreach($posts as $post){?>
    <li>
        <div class="uk-text-primary"><?= $post['content'] ?></div>
        <div class="uk-flex uk-text-small">
            <div class="uk-width-1-2"><span class="uk-text-middle"><a href="<?= Url::toRoute(['user/index','id'=>$post['user']['id']]) ?>"><?php if($post['user']['username']){echo Html::encode($post['user']['username']);}else{ echo Html::encode($post['user']['email']);} ?></a></span><span class="uk-margin-small-left uk-text-middle"><?= date("Y-m-d H:i:s",$post['created_t']) ?></span></div>
            <div class="uk-width-1-2 uk-text-right"><button class="uk-button uk-button-default uk-button-small private" uk-toggle="target:#modal-prive-<?= $post['user']['id'] ?>">回复</button></div>
        </div>
    </li>
        <div id="modal-prive-<?= $post['user']['id'] ?>" uk-modal class="uk-flex-top">
            <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <form>
                    <fieldset class="uk-fieldset">
                        <div class="uk-margin">
                            <textarea class="uk-textarea" id="pricontent-<?= $post['user']['id'] ?>" style="resize:none;" rows="5" placeholder="请填写私信内容(20字内)"></textarea>
                        </div>
                        <div class="uk-margin">
                            <button type="button" class="uk-button uk-button-primary uk-width-1-1 prisub" data="<?= $post['user']['id'] ?>" >提交</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    <hr class="uk-margin-small"/>
    <?php } ?>
    <?= LinkPager::widget(['pagination' => $pagination,'options' => ['class' => 'uk-pagination uk-flex-center'],'prevPageCssClass'=>'uk-pagination-previous','disabledPageCssClass'=>'uk-disabled','activePageCssClass'=>'uk-active','nextPageCssClass'=>'uk-pagination-next','prevPageLabel'=>'<span uk-pagination-previous></span>','nextPageLabel'=>'<span uk-pagination-next></span>']) ?>
</ul>
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
</script>