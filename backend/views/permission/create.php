<?php
use yii\helpers\Url;
$this->title=$title.'-论坛管理后台';
?>
<link href="/static/css/layui.css" rel="stylesheet" />
<form class="layui-form" action="">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" readonly/>
    <div class="layui-form-item">
        <label class="layui-form-label">父权限</label>
        <div class="layui-input-block">
            <select name="PermissionForm[fid]" lay-verify="required">
                <option value="0">一级菜单</option>
                <?php foreach($lists as $list){ ?>
                    <option value="<?= $list['id'] ?>"><?= $list['description'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">权限名</label>
        <div class="layui-input-block">
            <input type="text" name="PermissionForm[name]" lay-verify="required|menu_url" autocomplete="off" placeholder="请输入权限名" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">权限描述</label>
        <div class="layui-input-block">
            <input type="text" name="PermissionForm[description]" lay-verify="required|menu_name" autocomplete="off" placeholder="请输入权限描述" class="layui-input">
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
                var pattern =/^[\u4e00-\u9fa5]+(-)?[\u4e00-\u9fa5]+$/;
                if(!pattern.test(value)){
                    return '权限描述仅能为中文';
                }
                if(value.length>20){
                    return '权限描述不得超过20个字符';
                }
            },
            menu_url:function(value){
                var patterns=/[a-zA-z]+(\/){1}/;
                if(!patterns.test(value)){
                    return '请填写符合格式权限名';
                }
                if(value.length>20){
                    return '权限名不得超过20个字符';
                }
            }
        });
        form.on('submit(imagecretae)', function(data){
            $.ajax({
                type:'POST',
                async: false,
                url: "<?= Url::toRoute(['permission/create']) ?>",
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