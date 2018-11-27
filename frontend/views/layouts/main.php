<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<script src="/static/js/jquery-3.3.1.min.js"></script>
<script src="/static/js/uikit.min.js"></script>
<script src="/static/js/uikit-icons.min.js"></script>

<!--导航条-->
<?= $this->render('_header') ?>

<div class="uk-container uk-margin-top">
    <?= $this->render('_message') ?>
    <?= $content ?>
</div>

<!--尾部-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
