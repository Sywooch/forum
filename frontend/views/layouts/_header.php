<?php
use yii\helpers\Url;
use yii\helpers\Html;
$route=\Yii::$app->controller->getRoute();
?>
<div class="tm-page">
    <div class="tm-header-mobile uk-hidden@m">
        <nav class="uk-navbar-container uk-navbar" uk-navbar="">
            <div class="uk-navbar-left">
                <a class="uk-navbar-item uk-logo" href="javascript:void(0);">
                    <img src="https://demo.yootheme.com/themes/wordpress/2017/sonic/wp-content/uploads/logo.svg" class="uk-responsive-height" alt="Sonic">
                </a>
            </div>
            <div class="uk-navbar-right">
                <a class="uk-navbar-toggle" href="#tm-mobile" uk-toggle="">
                    <div uk-navbar-toggle-icon="" class="uk-navbar-toggle-icon uk-icon"></div>
                </a>
            </div>
        </nav>
        <div id="tm-mobile" class="uk-modal-full uk-modal" uk-modal="">
            <div class="uk-modal-dialog uk-modal-body uk-text-center uk-flex" uk-height-viewport="" style="box-sizing: border-box; min-height: 100vh; height: 100vh;">
                <button class="uk-modal-close-full uk-close uk-icon" type="button" uk-close=""></button>
                <div class="uk-margin-auto-vertical uk-width-1-1">
                    <div class="uk-child-width-1-1 uk-grid" uk-grid="">
                        <div>
                            <div class="uk-panel">
                                <ul class="uk-nav uk-nav-primary uk-nav-center">
                                    <li class=" menu-item menu-item-type-post_type menu-item-object-page menu-item-home  <?php if($route=='index/index'){ ?> uk-active <?php } ?>"><a href="<?= Url::home()?>">首页</a></li>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-20 current_page_item <?php if($route=='plate/index'){ ?> uk-active <?php } ?>"><a href="<?= Url::toRoute(['/plate'])?>">板块</a></li>
                                    <?php if(\Yii::$app->user->isGuest){ ?>
                                    <li class=" menu-item menu-item-type-post_type menu-item-object-page <?php if($route=='pass/login'){ ?> uk-active <?php } ?> "><a href="<?= Url::toRoute(['pass/login'])?>">登陆</a></li>
                                    <li class=" menu-item menu-item-type-post_type menu-item-object-page <?php if($route=='pass/register'){ ?> uk-active <?php } ?> "><a href="<?= Url::toRoute(['pass/register'])?>">注册</a></li>

                                    <?php }else{ ?>
                                    <li class=" menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children uk-parent">
                                        <a href="#"><?php if(\Yii::$app->user->identity->username){ echo \Yii::$app->user->identity->username; }else{ echo \Yii::$app->user->identity->email; } ?></a>
                                        <ul class="uk-nav-sub">
                                            <li class=" menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= Url::toRoute(['user/index','id'=>\Yii::$app->user->id]) ?>">个人主页</a></li>
                                            <li class=" menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= Url::toRoute(['user/index','id'=>\Yii::$app->user->id,'t'=>'send'])?>">发帖记录</a></li>
                                            <li class=" menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= Url::toRoute(['user/index','id'=>\Yii::$app->user->id,'t'=>'send'])?>">消息记录</a></li>
                                            <li class=" menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= Url::toRoute(['user/index','id'=>\Yii::$app->user->id,'t'=>'collection'])?>">收藏记录</a></li>
                                            <li class=" menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= Url::toRoute(['user/index','id'=>\Yii::$app->user->id,'t'=>'set'])?>">资料设置</a></li>
                                        </ul>
                                    </li>
                                        <li class=" menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= Url::toRoute(['pass/logout'])?>">退出</a></li>
                                    <?php } ?>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tm-header uk-visible@m tm-header-transparent uk-card uk-card-default uk-card-body uk-padding-remove" uk-header="">
        <div uk-sticky="" media="768" show-on-up="" animation="uk-animation-slide-top" cls-active="uk-navbar-sticky" sel-target=".uk-navbar-container" top=".tm-header ~ [class*=uk-section], .tm-header ~ * > [class*=uk-section]" cls-inactive="uk-navbar-transparent uk-dark" class="uk-sticky">
            <div class="uk-navbar-container uk-navbar-transparent">
                <div class="uk-container uk-container-large">
                    <nav class="uk-navbar" uk-navbar="{align:left,boundary:!.uk-navbar-container,dropbar:true,dropbar-anchor:!.uk-navbar-container,dropbar-mode:slide}">
                        <div class="uk-navbar-left">
                            <a href="javascript:void(0);" class="uk-navbar-item uk-logo">
                                <img src="https://demo.yootheme.com/themes/wordpress/2017/sonic/wp-content/uploads/logo.svg" class="uk-responsive-height" alt="Sonic">
                            </a>
                        </div>
                        <div class="uk-navbar-center">
                            <ul class="uk-navbar-nav">
                                <li <?php if($route=='index/index'){ ?> class="uk-active" <?php } ?> ><a href="<?= Url::home()?>" class=" menu-item menu-item-type-post_type menu-item-object-page menu-item-home">首页</a></li>
                                <li <?php if($route=='plate/index'){ ?> class="uk-active" <?php } ?> ><a href="<?= Url::toRoute(['/plate'])?>">板块</a></li>
                                <li <?php if($route=='pass/login'){ ?> class="uk-active" <?php } ?> ><a href="<?= Url::toRoute(['pass/login'])?>">关于我们</a></li>
                            </ul>
                        </div>
                        <div class="uk-navbar-right">
                            <div>
                                <a class="uk-navbar-toggle" uk-search-icon href="#"></a>
                                <div class="uk-drop" uk-drop="mode: click; pos: left-center; offset: 0">
                                    <form class="uk-search uk-search-navbar uk-width-1-1" action="<?= Url::toRoute(['index/search']) ?>">
                                        <input class="uk-search-input" name="search" type="search" placeholder="搜索..." autofocus>
                                    </form>
                                </div>
                            </div>

                            <ul class="uk-navbar-nav">
                                <?php if(\Yii::$app->user->isGuest){ ?>
                                <li><a href="<?= Url::toRoute(['pass/login'])?>" class=" menu-item menu-item-type-post_type menu-item-object-page menu-item-home">登陆</a></li>
                                <li><a href="<?= Url::toRoute(['pass/register'])?>" class=" menu-item menu-item-type-post_type menu-item-object-page menu-item-home">注册</a></li>
                                <?php }else{ ?>
                                <li class="uk-parent">
                                    <a href="#" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children" aria-expanded="false">
                                        <img class="uk-border-circle" style="width:40px;height:40px;margin-right:5px;" src="<?= \Yii::$app->user->identity->avatar ?>">
                                        <?php if(\Yii::$app->user->identity->username){ echo \Yii::$app->user->identity->username; }else{ echo \Yii::$app->user->identity->email; } ?>
                                    </a>
                                    <div class="uk-navbar-dropdown uk-navbar-dropdown-dropbar" style="left: 1458.94px; top: 106px;">
                                        <div class="uk-navbar-dropdown-grid uk-child-width-1-1 uk-grid uk-grid-stack" uk-grid="">
                                            <div class="uk-first-column">
                                                <ul class="uk-nav uk-navbar-dropdown-nav" style="width:150px;">
                                                    <li><a href="<?= Url::toRoute(['user/index','id'=>\Yii::$app->user->id]) ?>">个人主页</a></li>
                                                    <li><a href="<?= Url::toRoute(['user/index','id'=>\Yii::$app->user->id,'t'=>'send'])?>">发帖记录</a></li>
                                                    <li><a href="<?= Url::toRoute(['user/index','id'=>\Yii::$app->user->id,'t'=>'send'])?>">消息记录</a></li>
                                                    <li><a href="<?= Url::toRoute(['user/index','id'=>\Yii::$app->user->id,'t'=>'collection'])?>">收藏记录</a></li>
                                                    <li><a href="<?= Url::toRoute(['user/index','id'=>\Yii::$app->user->id,'t'=>'set'])?>">资料设置</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a href="<?= Url::toRoute(['pass/logout']) ?>" class=" menu-item menu-item-type-post_type menu-item-object-page menu-item-home">
                                    退出
                                    </a>
                                </li>
                                <?php } ?>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
            <div class="uk-navbar-dropbar uk-navbar-dropbar-slide" style="height: 0px;"></div>
        </div>
        <div class="uk-sticky-placeholder" style="height: 106px; margin: 0px;" hidden=""></div>
    </div>
</div>