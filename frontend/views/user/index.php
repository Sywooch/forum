<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="uk-flex uk-flex-column">
    <div class="uk-card uk-card-default uk-card-body uk-padding-small">
        <div class="uk-flex">
            <div class="uk-width-auto" style="margin:auto 0px;"><img src="<?= $user_info['avatar'] ?>" width="80" height="80"/></div>
            <div class="uk-width-expand uk-margin-small-left">
                <div class="uk-flex">
                    <div class="uk-width-auto"><h4 class="uk-margin-remove-bottom"><?php if($user_info['username']){echo Html::encode($user_info['username']);}else{ echo Html::encode($user_info['email']);} ?>(<?= $appellation?>)</div>
                    <div class="uk-width-expand uk-text-small uk-text-muted uk-text-right"><?php if(\Yii::$app->user->id!=$id){?> <a href="javascript:void(0)" class="private" uk-toggle="target:#modal-prive">发消息</a> <?php } ?></div>
                </div>
                <div class="uk-text-small uk-column-1-4 uk-column-divider uk-text-center uk-margin-small-top">
                    <p>等级<br/><b><?= $user_info['level'] ?></b></p>
                    <p>积分<br/><b><?= $user_info['integral'] ?></b></p>
                    <p>签到<br/><b><?= $sign ?></b></p>
                    <p>访客<br/><b><?= $user_info['view'] ?></b></p>
                </div>
            </div>
        </div>
    </div>
    <div class="uk-card uk-card-default uk-card-body uk-padding-small uk-hidden@l">
        <ul class="uk-subnav uk-margin-remove-bottom">
            <li <?php if($t=='user'){ ?> class="uk-active" <?php }?>><a href="#">个人主页</a></li>
            <li <?php if($t=='send'){ ?> class="uk-active" <?php }?> ><a href="#">发帖</a></li>
            <li <?php if($t=='reply'){ ?> class="uk-active" <?php }?> ><a href="#">回帖</a></li>
            <li <?php if($t=='site'){ ?> class="uk-active" <?php }?> ><a href="#">站内消息</a></li>
            <li <?php if($t=='private'){ ?> class="uk-active" <?php }?> ><a href="#">私人消息</a></li>
            <li <?php if($t=='collection'){ ?> class="uk-active" <?php }?> ><a href="#">收藏</a></li>
        </ul>
    </div>
    <div class="uk-margin-top" uk-grid>
        <div class="uk-visible@m uk-width-1-5@l">
            <div class="uk-card uk-card-default uk-card-body">
                <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
                    <li  <?php if($t=='user'){ ?> class="uk-active" <?php }?> ><a href="<?= Url::toRoute(['user/index','t'=>'user','id'=>$id])?>"><span class="uk-margin-small-right" uk-icon="icon: user"></span> 个人主页</a></li>
                    <li class="uk-nav-divider"></li>
                    <li <?php if($t=='send'){ ?> class="uk-active" <?php }?> ><a href="<?= Url::toRoute(['user/index','t'=>'send','id'=>$id])?>"><span class="uk-margin-small-right" uk-icon="icon: file"></span> 发帖</a></li>
                    <li class="uk-nav-divider"></li>
                    <li <?php if($t=='reply'){ ?> class="uk-active" <?php }?> ><a href="<?= Url::toRoute(['user/index','t'=>'reply','id'=>$id])?>"><span class="uk-margin-small-right" uk-icon="icon: file"></span> 回帖</a></li>
                    <li class="uk-nav-divider"></li>
                    <?php if(\Yii::$app->user->id&&$id==\Yii::$app->user->id){?>
                    <li class="uk-parent  <?php if($t=='private'||$t=='site'){ ?> uk-active <?php }?>">
                        <a href="#"><span class="uk-margin-small-right" uk-icon="icon: commenting"></span> 消息</a>
                        <ul class="uk-nav-sub">
                            <li><a href="<?= Url::toRoute(['user/index','t'=>'site','id'=>$id])?>">站内消息</a></li>
                            <li><a href="<?= Url::toRoute(['user/index','t'=>'private','id'=>$id])?>">私人消息</a></li>
                        </ul>
                    </li>
                    <li class="uk-nav-divider"></li>
                    <li <?php if($t=='collection'){ ?> class="uk-active" <?php }?> ><a href="<?= Url::toRoute(['user/index','t'=>'collection','id'=>$id])?>"><span class="uk-margin-small-right" uk-icon="icon: heart"></span> 收藏</a></li>
                    <li class="uk-nav-divider"></li>
                    <li class="uk-parent <?php if($t=='set'){ ?> uk-active <?php }?> ">
                        <a href="#"><span class="uk-margin-small-right" uk-icon="icon: settings"></span> 设置</a>
                        <ul class="uk-nav-sub">
                            <li><a href="<?= Url::toRoute(['user/index','t'=>'set','id'=>$id])?>">基本资料</a></li>
                            <li><a href="<?= Url::toRoute(['user/index','t'=>'pass_reset','id'=>$id])?>">密码重置</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="uk-width-1-1@m uk-width-4-5@l">
            <div class="uk-card uk-card-default uk-card-body uk-padding-small">
                <?= $this->render("_$t",['counts'=>isset($counts)?$counts:'','countss'=>isset($countss)?$countss:'','posts'=>isset($posts)?$posts:'','pagination'=>isset($pagination)?$pagination:'','post_v'=>isset($counts_v)?$counts_v:'','model'=>isset($model)?$model:'','id'=>$id,'user_info'=>isset($user_info)?$user_info:'']) ?>
            </div>
        </div>
    </div>
</div>
<?php if(\Yii::$app->user->id!=$id){?>
<div id="modal-prive" uk-modal class="uk-flex-top">
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <form>
            <fieldset class="uk-fieldset">
                <div class="uk-margin">
                    <textarea class="uk-textarea" id="pricontent" style="resize:none;" rows="5" placeholder="请填写私信内容(20字内)"></textarea>
                </div>
                <div class="uk-margin">
                    <button type="button" class="uk-button uk-button-primary uk-width-1-1 prisub" data="<?= $id?>">提交</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<?php } ?>
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
</script>