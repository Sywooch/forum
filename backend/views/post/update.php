<?php
use yii\helpers\Url;
$this->title=$title.'-论坛管理后台';
?>
<link href="/static/css/layui.css" rel="stylesheet" />
<link href="/static/editor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<form class="layui-form">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" readonly/>
    <input type="hidden" name="PostForm[id]" value="<?= $info['id'] ?>" readonly/>
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input type="text" name="PostForm[title]" lay-verify="required|title" autocomplete="off" value="<?= $info['title'] ?>" placeholder="请输入标题" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
          <textarea type="text/plain" id="myEditor" name="PostForm[content]" lay-verify="required" style="width:1000px;height:450px;">
              <?= $info['content'] ?>
          </textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="imagecretae">立即提交</button>
        </div>
    </div>
</form>

<script>var csrfs="<?= \Yii::$app->request->csrfToken ?>";</script>
<script src="/static/js/jquery-3.3.1.min.js"></script>
<script src="/static/layui.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/editor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/editor/umeditor.min.js"></script>
<script type="text/javascript" src="/static/editor/lang/zh-cn/zh-cn.js"></script>
<script>
    var um = UM.getEditor('myEditor',{
        imageUrl:"<?= Url::toRoute(['image/upload']) ?>",
        imagePath:'',
        imageFieldName:"imageFile",
        toolbar:[
            'undo redo | bold italic underline strikethrough | forecolor backcolor | removeformat |',
            'insertorderedlist insertunorderedlist | selectall cleardoc paragraph | fontfamily fontsize' ,
            '| justifyleft justifycenter justifyright justifyjustify | emotion image horizontal preview',
        ]
    });
    layui.use(['form'], function(){
        var form = layui.form;

        form.on('submit(imagecretae)', function(data){
            $.ajax({
                type:'POST',
                async: false,
                url: "<?= Url::toRoute(['post/update','id'=>$info['id']]) ?>",
                data:data.field,
                success: function (result) {
                    if(result.code!=0){
                        layer.msg(result.info,{icon:2,time:1000});return false;
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