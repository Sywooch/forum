<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = '错误请求';
?>
<!--<div class="site-error">

    <h1><?/*= Html::encode($this->title) */?></h1>

    <div class="alert alert-danger">
        <?/*= nl2br(Html::encode($message)) */?>
    </div>

</div>-->

<div class="uk-alert-danger" uk-alert>
    <a class="uk-alert-close" uk-close></a>
    <p><?= nl2br(Html::encode($message)) ?></p>
</div>
