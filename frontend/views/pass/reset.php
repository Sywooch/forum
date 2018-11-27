<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="uk-card uk-card-default uk-card-body">
    <form action="<?= Url::toRoute(['pass/reset','token'=>$token],true) ?>" method="post">
        <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>"/>
        <div class="uk-margin uk-width-1-2" style="margin:0px auto;">
            <p>请填写新密码：</p>
            <input class="uk-input <?php if($model->hasErrors('password')){ ?>uk-form-danger <?php } ?>" type="password" placeholder="新密码" name="ResetPasswordForm[password]">
            <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'password', ['class' => 'error']) ?></span>
        </div>
        <div class="uk-margin uk-width-1-2" style="margin:0px auto;">
            <input class="uk-input <?php if($model->hasErrors('repassword')){ ?>uk-form-danger <?php } ?>" type="password" placeholder="确认密码" name="ResetPasswordForm[repassword]">
            <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'repassword', ['class' => 'error']) ?></span>
        </div>
        <div class="uk-margin uk-width-1-2 uk-text-center" style="margin:0px auto;">
            <button class="uk-button uk-button-primary">提交</button>
        </div>
    </form>
</div>