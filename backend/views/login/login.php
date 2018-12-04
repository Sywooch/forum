<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
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
        <title>管理后台登陆</title>
        <?php $this->head() ?>
    </head>
    <body style="background-color:#f2f2f2">
    <?php $this->beginBody() ?>
    <div class="layui-main">
        <div class="layui-row">
            <div class="layui-col-md6" style="height:200px;">
            </div>
        </div>
        <div class="layui-row">
            <div class="layui-col-md12" >
                <div style="width:50%;margin-left:20%;">
                    <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login">
                        <div class="layadmin-user-login-main">
                            <div style="text-align:center;margin-bottom:20px;">
                                <h2>后台管理</h2>
                            </div>
                            <form class="layui-form" action="">
                                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>"/>
                                <div class="layui-form-item">
                                    <input type="text" name="LoginForm[username]" lay-verify="required|username" placeholder="用户名" class="layui-input">
                                </div>
                                <div class="layui-form-item">
                                    <input type="password" name="LoginForm[password]" lay-verify="required|password" placeholder="密码" class="layui-input">
                                </div>
                                <div class="layui-form-item" style="margin-bottom:20px;">
                                    <input type="checkbox" name="LoginForm[remember]" lay-skin="primary" title="记住密码"><div class="layui-unselect layui-form-checkbox" lay-skin="primary"><span>记住密码</span><i class="layui-icon layui-icon-ok"></i></div>
                                </div>
                                <div class="layui-form-item">
                                    <button class="layui-btn layui-btn-fluid" lay-submit="" lay-filter="imagecretae">立即提交</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/static/layui.js"></script>
    <script>
        layui.use(['element','form'], function(){
            var form = layui.form
                ,$ = layui.jquery
                ,upload = layui.upload
                ,layer = layui.layer;
            form.verify({
                username:function(value){
                    var pattern =/^[0-9a-zA-Z]+$/;
                    if(!pattern.test(value)){
                        return '用户名仅支持英文和数字';
                    }
                    if(value.length>20){
                        return '用户名不得大于20个字符';
                    }
                },
                password:function(value){
                    var patterns=/^[0-9a-zA-Z]+$/;
                    if(!patterns.test(value)){
                        return '用户名仅支持英文和数字';
                    }
                    if(value.length>20){
                        return '密码不得大于20个字符';
                    }
                }
            });
            form.on('submit(imagecretae)', function(data){
                $.ajax({
                    type:'POST',
                    async: false,
                    url: "<?= Url::toRoute(['login/index'])?>",
                    data:data.field,
                    success: function (res) {
                        res.code==1?layer.msg(res.info,{icon:5}):layer.msg(res.info,{icon:1,time:1000},function(){
                            window.location.href="<?= Url::home() ?>";
                        });
                    }
                });
                return false;
            });
        });
    </script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>