<?php foreach(array('success','warning','danger') as $v){ if(!empty(\Yii::$app->session->getFlash($v))){ ?>
<div class="uk-alert-<?php echo $v;?>" uk-alert>
    <a class="uk-alert-close" uk-close></a>
    <p><?= \Yii::$app->session->getFlash($v) ?></p>
</div>
<?php } } ?>