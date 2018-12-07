<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="uk-child-width-1-3@m uk-grid-small uk-grid-match" uk-grid>
    <?php foreach($plates as $plate){?>
    <div class="uk-animation-toggle">
        <div class="uk-card uk-card-default uk-card-body uk-animation-scale-up">
            <h3 class="uk-card-title uk-text-center"><a href="<?= Url::toRoute(['/post','id'=>$plate['fid'],'s'=>$plate['id']]) ?>"> <?= $plate['name'] ?></a></h3>
        </div>
    </div>
    <?php } ?>
</div>