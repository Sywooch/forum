<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= \Yii::$app->language ?>">
    <head>
        <meta charset="<?= \Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>管理后台登陆</title>
        <link rel="stylesheet" type="text/css" href="/static/css/normalize.css" />
        <link rel="stylesheet" type="text/css" href="/static/css/demo.css" />
        <link rel="stylesheet" type="text/css" href="/static/css/component.css" />
    </head>
    <body>
    <?php $this->beginBody() ?>
    <div class="container demo-1">
        <div class="content">
            <div id="large-header" class="large-header">
                <canvas id="demo-canvas"></canvas>
                <div class="logo_box">
                    <h3>欢迎你</h3>
                    <form action="<?= Url::toRoute(['login/login']) ?>" method="post">
                        <input type="hidden" value="<?= \Yii::$app->request->csrfToken ?>" name="_csrf" readonly>
                        <div class="input_outer">
                            <span class="u_user"></span>
                            <input name="LoginForm[email]" class="text" style="color: #FFFFFF !important" type="text" value="<?= $model->email?>" placeholder="请输入邮箱" />
                            <span><?= Html::error($model,'email',['class'=>'error']) ?></span>
                        </div>
                        <div class="input_outer">
                            <span class="us_uer"></span>
                            <input name="LoginForm[password]" class="text" style="color: #FFFFFF !important; position:absolute; z-index:100;"value="" type="password" placeholder="请输入密码">
                        </div>
                        <span><?= Html::error($model,'password',['class'=>'error']) ?></span>
                        <div class="mb2"><button class="act-but submit" type="submit" style="color: #FFFFFF;width:100%">登录</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="/static/js/TweenLite.min.js"></script>
    <script src="/static/js/EasePack.min.js"></script>
    <script src="/static/js/rAF.js"></script>
    <script src="/static/js/demo-1.js"></script>
    <div style="text-align:center;">
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>