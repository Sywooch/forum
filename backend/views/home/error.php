<?php
use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">
    <blockquote class="layui-elem-quote">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= nl2br(Html::encode($message)) ?>
    </blockquote>
</div>
<script>
    var s="<?= \Yii::$app->request->csrfToken?>";
    layui.use(['element'], function(){
        var element = layui.element;
    });
</script>