<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<link href="/static/editor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<div class="uk-flex uk-flex-center">
    <div class="uk-card uk-card-default uk-card-body uk-width-1-1@m uk-width-2-3@l uk-padding-small">
        <ul class="uk-breadcrumb">
            <li><a href="<?= Url::toRoute('index/index') ?>">首页</a></li>
            <li><span>发布</span></a></li>
        </ul>
        <ul uk-tab>
            <li><a href="#">发布帖子</a></li>
            <!--<li><a href="#">发布悬赏</a></li>-->
        </ul>

        <ul class="uk-switcher uk-margin">
            <li>
                <form class="uk-grid-small" uk-grid action="<?= Url::toRoute(['post/create']) ?>" method="post">
                    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>"/>
                    <div class="uk-width-1-3">
                        <select class="uk-select" name="CreateForm[plate]">
                            <option>选择主题分类</option>
                            <?php foreach($plates as $plate){ ?>
                                <option value="<?= $plate['id'] ?>"><?= $plate['name'] ?></option>
                            <?php }?>
                        </select>
                        <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'plate', ['class' => 'error']) ?></span>
                    </div>
                    <div class="uk-width-2-3">
                        <input class="uk-input" type="title" name="CreateForm[title]" placeholder="标题（仅限30个字符）">
                        <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'title', ['class' => 'error']) ?></span>
                    </div>
                    <div class="uk-width-1-1">
                        <textarea type="text/plain" id="content" name="CreateForm[content]" style="width:100%;"></textarea>
                        <span class="uk-text-small uk-text-danger"><?= Html::error($model, 'content', ['class' => 'error']) ?></span>
                    </div>
                    <div class="uk-width-1-1">
                        <span class="uk-margin-small-right" uk-icon="info"></span><a uk-toggle="target: #toggle-usage">附加选项</a>
                        <p id="toggle-usage" hidden aria-hidden="true" class="uk-margin-remove-top uk-text-small">
                            <label><input class="uk-checkbox" type="checkbox" name="CreateForm[reply]" checked>接收回复通知</label>
                        </p>
                    </div>
                    <div class="uk-width-1-1 uk-flex">
                        <div class="uk-width-1-1"><button type="submit" class="uk-button uk-button-default uk-width-1-1">发表帖子</button></div>
                        <!--<div class="uk-width-1-2"><button type="submit" class="uk-button uk-button-default uk-width-1-1">保存草稿</button></div>-->
                    </div>
                </form>
            </li>
            <li>
                <form class="uk-grid-small" uk-grid action="<? Url::toRoute(['post/create']) ?>" >
                    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>"/>
                    <div class="uk-width-1-5">
                        <select class="uk-select">
                            <option>选择主题分类</option>
                            <option>分享交流</option>
                            <option>玩机技巧</option>
                            <option>问题反馈</option>
                        </select>
                    </div>
                    <div class="uk-width-3-5">
                        <input class="uk-input" type="text" name="CreateForm[title]" placeholder="标题（仅限30个字符）">
                    </div>
                    <div class="uk-width-1-5">
                        <input class="uk-input" type="text" name="CreateForm[price]" placeholder="悬赏价" uk-tooltip="不得低于1，不得高于5<br/>您今天还可以免费使用25威望用于发悬赏贴哦<br/>7天后如果您仍未设置最佳答案,版主有权代为您选择">
                    </div>
                    <div class="uk-width-1-1">
                        <textarea type="text/plain" id="contents" name="CreateForm[content]" style="width:700px;"><p>您可以输入10000字符</p></textarea>
                    </div>
                    <div class="uk-width-1-1">
                        <span class="uk-margin-small-right" uk-icon="info"></span><a uk-toggle="target: #toggle-usage">附加选项</a>
                        <p id="toggle-usage" hidden aria-hidden="true" class="uk-margin-remove-top uk-text-small">
                            <!--<label><input class="uk-checkbox" type="checkbox">回帖仅作者可见</label>-->
                            <label><input class="uk-checkbox" type="checkbox" checked>接受回复通知</label>
                        </p>
                    </div>
                    <div class="uk-width-1-1 uk-flex">
                        <div class="uk-width-1-2"><button type="submit" class="uk-button uk-button-default uk-width-1-1">发表帖子</button></div>
                        <div class="uk-width-1-2"><button type="submit" class="uk-button uk-button-default uk-width-1-1">保存草稿</button></div>
                    </div>
                </form>
            </li>
        </ul>
    </div>
</div>
<script>var csrfs="<?= \Yii::$app->request->csrfToken ?>";</script>
<script type="text/javascript" charset="utf-8" src="/static/editor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/editor/umeditor.min.js"></script>
<script type="text/javascript" src="/static/editor/lang/zh-cn/zh-cn.js"></script>
<script>
    var um = UM.getEditor('content',{
        imageUrl:"<?= Url::toRoute(['image/upload']) ?>",
        imagePath:'',
        imageFieldName:"imageFile",
        toolbar:[
            'undo redo | bold italic underline strikethrough | forecolor backcolor | removeformat |',
            'insertorderedlist insertunorderedlist | selectall cleardoc paragraph | fontfamily fontsize' ,
            '| justifyleft justifycenter justifyright justifyjustify | emotion image horizontal preview',
        ]
    });
</script>




