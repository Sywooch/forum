<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>注册---BBS论坛</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<script src="/static/js/jquery-3.3.1.min.js"></script>
<script src="/static/js/uikit.min.js"></script>
<script src="/static/js/uikit-icons.min.js"></script>

<div class="uk-flex uk-flex-center uk-flex-middle uk-width-1-1" style="height:100%;">
    <div class="uk-width-large uk-flex uk-flex-column">
        <div class="uk-margin-small uk-card uk-card-default uk-card-body uk-padding-small">
            <form action="<?= Url::toRoute(['pass/register'])?>" method="post">
                <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken?>" />
                <div class="uk-margin">
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon:user"></span>
                        <input class="uk-input <?php if($model->hasErrors('email')){ echo 'uk-form-danger'; } ?>" type="text" name="PassForm[email]" placeholder="邮箱" value="<?= $model->email ?>" />
                    </div>
                    <span class="uk-text-small uk-text-danger"><?= Html::error($model,'email',['class'=>'error']) ?></span>
                </div>
                <div class="uk-margin">
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon:lock"></span>
                        <input class="uk-input <?php if($model->hasErrors('password')){ echo 'uk-form-danger'; } ?>" type="password" name="PassForm[password]" placeholder="密码">
                    </div>
                    <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'password', ['class' => 'error']) ?></span>
                </div>
                <div class="uk-margin">
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                        <input class="uk-input <?php if($model->hasErrors('repassword')){ echo 'uk-form-danger'; } ?>" type="password" name="PassForm[repassword]" placeholder="确认密码">
                    </div>
                    <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'repassword', ['class' => 'error']) ?></span>
                </div>
                <div class="uk-margin" uk-grid>
                    <div class="uk-width-2-3">
                        <input class="uk-input <?php if($model->hasErrors('verifyCode')){ echo 'uk-form-danger'; } ?> " type="text" name="PassForm[verifyCode]" placeholder="验证码">
                    </div>
                    <div class="uk-width-1-3">
                        <img id="imgVerifyCode" src="<?= Url::toRoute(['pass/captcha']) ?>" onclick="changeCap()" alt="验证码"/>
                    </div>
                    <div class="uk-width-1-1 uk-margin-remove"><span class="uk-text-small uk-text-danger"><?= Html::error($model, 'verifyCode', ['class' => 'error']) ?></span></div>
                </div>
                <div class="uk-margin">
                    <button class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom">注册</button>
                </div>
            </form>
        </div>
        <div class="uk-margin-small uk-card uk-card-default uk-card-body uk-padding-small uk-text-center">
            <a href="<?= Url::toRoute(['pass/login'])?>">去登陆</a>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
<?php $this->endPage() ?>