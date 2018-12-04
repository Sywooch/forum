<?php
use yii\helpers\Url;
$route=Yii::$app->controller->getRoute();
$UserMenu=Yii::$app->session->get('user_menu');
?>
<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <ul class="layui-nav layui-nav-tree"  lay-filter="test">
            <li class="layui-nav-item"><a href="<?= Url::toRoute(['home/index']) ?>">主页</a></li>
            <?php foreach($UserMenu as $v){ ?>
            <li class="layui-nav-item <?php if(in_array($route,$v['child_url'])){ ?> layui-nav-itemed <?php }?> ">
                <a class="" href="javascript:;"><?= $v['menu_name'] ?></a>
                <?php if(!empty($v['child'])){  ?>
                <dl class="layui-nav-child">
                    <?php foreach($v['child'] as $vs){ ?>
                    <dd <?php if($route==$vs['menu_url']){ ?> class="layui-this" <?php }?> ><a href="<?= Url::toRoute([$vs['menu_url']]) ?>"><?= $vs['menu_name'] ?></a></dd>
                    <?php } ?>
                </dl>
                <?php } ?>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
