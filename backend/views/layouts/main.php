<?php
use backend\assets\AppAsset;
use yii\helpers\Html;

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
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="layui-layout-body">
<?php $this->beginBody() ?>
<script src="/static/js/jquery-3.3.1.min.js"></script>
<script src="/static/layui.js"></script>
<div class="layui-layout layui-layout-admin">
    <?= $this->render('_header') ?>

    <?= $this->render('_nav') ?>

    <div class="layui-body">

        <div style="padding: 15px;"><?= $content ?></div>
    </div>

    <?= $this->render('_footer') ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
