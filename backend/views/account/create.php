<?php
use yii\helpers\Url;
$this->title=$title.'-论坛管理后台';
?>
<link href="/static/css/layui.css" rel="stylesheet" />
<form class="layui-form" action="">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" readonly/>
    <div class="layui-form-item">
        <label class="layui-form-label">用户身份</label>
        <div class="layui-input-block">
            <select name="AccountForm[role]" lay-verify="required">
                <option value="">请选择用户身份</option>
                <?php foreach($lists as $list){ ?>
                <option value="<?= $list['name'] ?>"><?= $list['description'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">
            <input type="text" name="AccountForm[username]" lay-verify="required|username" autocomplete="off" placeholder="请输入用户名" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-block">
            <input type="password" name="AccountForm[password]" lay-verify="required|password" autocomplete="off" placeholder="请输入密码" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="imagecretae">立即提交</button>
        </div>
    </div>
</form>
<script type="text/javascript" src="/static/layui.js"></script>
<script>
    var s="<?= \Yii::$app->request->csrfToken?>";
    layui.use(['form'], function(){
        var form = layui.form
            ,$ = layui.jquery
            ,layer = layui.layer;

        form.verify({
            username:function(value){
                var pattern =/^[a-zA-Z1-9]+$/;
                if(!pattern.test(value)){
                    return '用户名仅能为英文、数字';
                }
                if(value.length>30){
                    return '用户名不得超30个字符';
                }
            },
            password:function(value){
                if(value.length>20){
                    return '密码不得超过20个字符';
                }
            }
        });
        form.on('submit(imagecretae)', function(data){
            $.ajax({
                type:'POST',
                async: false,
                url: "<?= Url::toRoute(['account/create']) ?>",
                data:data.field,
                success: function (result) {
                    if(result.code!=0){
                        layer.msg(result.info,{icon:2,time:1000});
                        return false;
                    }
                    layer.msg(result.info,{icon:1,time:1000},function(){
                        location.reload();
                    });
                }
            });
            return false;
        });
    });
</script>