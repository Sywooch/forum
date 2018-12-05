<?php
use yii\helpers\Url;
$this->title=$title.'-论坛管理后台';
?>
<link href="/static/css/layui.css" rel="stylesheet" />
<div class="layui-container">
    <div class="layui-row">
        <div class="layui-col-md6 layui-col-md-offset2">
            <fieldset class="layui-elem-field layui-field-title"><legend>请选择用户权限</legend></fieldset>
            <form class="layui-form">
                <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" readonly/>
                <input type="hidden" name="TakeForm[role]" value="<?= $id ?>" readonly/>
                <div class="layui-form-item">
                    <label class="layui-form-label">选择权限</label>
                    <div class="layui-input-block">
                        <div id="LAY-auth-tree-index"></div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-fluid" type="submit" lay-submit lay-filter="LAY-auth-tree-submit">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="/static/layui.js"></script>
<script type="text/javascript">
    var s="<?= \Yii::$app->request->csrfToken?>";
    layui.config({
        base:'/static/',
    }).extend({
        authtree: 'authtree',
    });
    layui.use(['jquery', 'authtree', 'form', 'layer'], function(){
        var $ = layui.jquery;
        var authtree = layui.authtree;
        var form = layui.form;
        var layer = layui.layer;
        $.ajax({
            url: "<?= Url::toRoute(['permission/tree']) ?>",
            dataType: 'json',
            method:'post',
            data:{_csrf:s,role:"<?= $id?>"},
            success: function(data){
                authtree.render('#LAY-auth-tree-index',data.data.trees,{inputname:'authids[]', layfilter: 'lay-check-auth', openall:false});
            }
        });
        form.on('submit(LAY-auth-tree-submit)', function(obj){
            if($("input[name='TakeForm[role]']")==''){layer.msg('请选择授权角色',{icon:2,time:1000});return false;}
            var authids = authtree.getChecked('#LAY-auth-tree-index');
            if(authids.length<=0){layer.msg('请选择权限',{icon:2,time:1000});return false;}
            $.ajax({
                type:'POST',
                url: "<?= Url::toRoute(['permission/take']) ?>",
                dataType: 'json',
                data: obj.field,
                success: function(result){
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
