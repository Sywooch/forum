<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="uk-flex uk-flex-center">
    <div class="uk-width-1-2">
        <form action="<?= Url::toRoute(['user/index','t'=>'pass_reset','id'=>$id])?>" method="post">
            <input type="hidden" type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>"/>
            <div class="uk-margin">
                <input class="uk-input <?php if($model->hasErrors('old')){ echo 'uk-form-danger'; } ?>" type="password" name="UpdateForm[old]" placeholder="旧密码">
                <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'old', ['class' => 'error']) ?></span>
            </div>
            <div class="uk-margin">
                <input class="uk-input <?php if($model->hasErrors('new')){ echo 'uk-form-danger'; } ?>" type="password" name="UpdateForm[new]" placeholder="新密码">
                <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'new', ['class' => 'error']) ?></span>
            </div>
            <div class="uk-margin">
                <input class="uk-input <?php if($model->hasErrors('news')){ echo 'uk-form-danger'; } ?>" type="password" name="UpdateForm[news]" placeholder="确认密码">
                <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'news', ['class' => 'error']) ?></span>
            </div>
            <div class="uk-margin">
                <button class="uk-button uk-button-default uk-width-1-1">确定</button>
            </div>
        </form>
    </div>
</div>