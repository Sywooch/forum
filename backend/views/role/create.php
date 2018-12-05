<?php
use yii\helpers\Url;
$this->title=$title.'-论坛管理后台';
?>
<link href="/static/css/layui.css" rel="stylesheet" />
<form class="layui-form" action="">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" readonly/>
    <div class="layui-form-item">
        <label class="layui-form-label">角色名</label>
        <div class="layui-input-block">
            <input type="text" name="RoleForm[name]" lay-verify="required|menu_url" autocomplete="off" placeholder="请输入角色名" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色描述</label>
        <div class="layui-input-block">
            <input type="text" name="RoleForm[description]" lay-verify="required|menu_name" autocomplete="off" placeholder="请输入角色描述" class="layui-input">
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
            menu_name:function(value){
                var pattern =/^[\u4e00-\u9fa5]+$/;
                if(!pattern.test(value)){
                    return '角色描述仅能为中文';
                }
                if(value.length>20){
                    return '角色描述不得超过20个字符';
                }
            },
            menu_url:function(value){
                var patterns=/[a-zA-z]+/;
                if(!patterns.test(value)){
                    return '请填写符合格式角色名';
                }
                if(value.length>20){
                    return '角色名不得超过20个字符';
                }
            }
        });
        form.on('submit(imagecretae)', function(data){
            $.ajax({
                type:'POST',
                async: false,
                url: "<?= Url::toRoute(['role/create']) ?>",
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