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
    <title>登陆---BBS论坛</title>
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
            <form action="<?= Url::toRoute(['pass/login'])?>" method="post">
                <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>"/>
                <div class="uk-margin">
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                        <input class="uk-input <?php if($model->hasErrors('email')){ ?>uk-form-danger <?php } ?>" type="text" placeholder="邮箱" name="LoginForm[email]" value="<?= $model->email ?>"/>
                    </div>
                    <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'email', ['class' => 'error']) ?></span>
                </div>
                <div class="uk-margin">
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                        <input class="uk-input <?php if($model->hasErrors('password')){ ?>uk-form-danger <?php } ?>" type="password" placeholder="密码" name="LoginForm[password]" value="<?= $model->password ?>" />
                    </div>
                    <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'password', ['class' => 'error']) ?></span>
                </div>

                <div class="uk-margin">
                    <div class="uk-inline uk-width-1-1">
                        <label><input class="uk-checkbox" type="checkbox" name="LoginForm[rememberMe]" value="1"> 记住我</label>
                    </div>
                </div>
                <div class="uk-margin">
                    <button class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom">登录</button>
                </div>
            </form>
        </div>
        <div class="uk-margin-small uk-card uk-card-default uk-card-body uk-padding-small uk-text-center">
            <div uk-grid>
                <div class="uk-width-1-2">
                    <div class="uk-text-left">没有帐号？<a href="<?= Url::toRoute(['pass/register']) ?>">去注册</a></div>
                </div>
                <div class="uk-width-1-2">
                    <div class="uk-text-right"><a href="<?= Url::toRoute(['pass/forget']) ?>">忘记密码？</a></div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->endBody() ?>
</body>
<?php $this->endPage() ?>


