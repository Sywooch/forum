<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<link href="/static/css/layui.css" rel="stylesheet">
<link href="/static/css/site.css" rel="stylesheet">
<script src="/static/layui.js"></script>

<div>
    <form class="layui-form" action="<?= Url::toRoute(['user/edit'],true)?>" method="post" style="padding:25px 20px 0px 0px;">
        <input type="hidden" name="_csrf"  value="<?= \Yii::$app->request->csrfToken ?>" readonly/>
        <input type="hidden" name="UupdateForm[id]" value="<?= $user->id ?>" readonly/>
        <div class="layui-form-item">
            <label class="layui-form-label">昵称</label>
            <div class="layui-input-block">
                <input type="text" name="UupdateForm[username]" lay-verify="required|username" placeholder="请输入" value="<?php if($user->username){echo $user->username;}?>" autocomplete="off" class="layui-input">
            </div>
            <span><?= Html::error($model,'username',['class'=>'error']) ?></span>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <select name="UupdateForm[status]">
                    <option value=""></option>
                    <option value="1" <?php if($user->status=='1'){ echo 'selected=""'; } ?> >未激活</option>
                    <option value="2" <?php if($user->status=='2'){ echo 'selected=""'; } ?> > 禁用</option>
                    <option value="10"<?php if($user->status=='10'){ echo 'selected=""'; } ?> >正常</option>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">组别</label>
            <div class="layui-input-block">
                <select name="UupdateForm[groups]">
                    <option value=""></option>
                    <option value="1" <?php if($user->status=='1'){ echo 'selected=""'; } ?> >普通用户</option>
                    <option value="2" <?php if($user->status=='2'){ echo 'selected=""'; } ?> >版主</option>
                    <option value="10" <?php if($user->status=='10'){ echo 'selected=""'; } ?> >管理员</option>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="editUser">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</div>
<script>
    layui.use(['form','layer'], function(){
        var form = layui.form
            ,layer = layui.layer;
        //自定义验证规则
        form.verify({
            username: function(value){
                if(value.length>10){
                    return '昵称最多10个字符';
                }
            },
        });
        //监听提交
        form.on('submit(editUser)', function(data){
            /*layer.alert(JSON.stringify(data.field), {
                title: '最终的提交信息'
            })*/
        });

    });
</script>
<?php if(Yii::$app->session->getFlash('success')){?>
    <script>
        var errors="<?= Yii::$app->session->getFlash('success') ?>";
        layui.use(['form','layer'], function() {
            layer.msg(errors);
        });
    </script>
<?php } ?>
