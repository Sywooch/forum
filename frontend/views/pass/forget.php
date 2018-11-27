<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="uk-card uk-card-default uk-card-body">
    <form action="<?= Url::toRoute(['pass/forget']) ?>" method="post">
        <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>"/>
        <div class="uk-margin uk-width-1-2" style="margin:0px auto;">
            <p>请填写用来接受激活密码的邮箱：</p>
            <input class="uk-input <?php if($model->hasErrors('email')){ ?>uk-form-danger <?php } ?>" type="text" placeholder="邮箱" value="<?= $model->email ?>" name="PasswordResetRequestForm[email]">
            <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'email', ['class' => 'error']) ?></span>
        </div>
        <div class="uk-margin uk-width-1-2 uk-text-center" style="margin:0px auto;">
            <button class="uk-button uk-button-primary">提交</button>
        </div>
    </form>
</div>


