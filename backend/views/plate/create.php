<?php
use yii\helpers\Url;
$this->title=$title.'-论坛管理后台';
?>
<link href="/static/css/layui.css" rel="stylesheet" />
<link href="/static/css/site.css" rel="stylesheet" />


<form class="layui-form">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" readonly/>
    <?php if(!empty($plates)){?>
        <div class="layui-form-item">
            <label class="layui-form-label">上级版区</label>
            <div class="layui-input-block">
                <select name="PlateForm[fid]">
                    <option value="">主版区</option>
                    <?php foreach($plates as $plate){ ?>
                    <option value="<?= $plate['id'] ?>"><?= $plate['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    <?php }?>
    <div class="layui-form-item">
        <label class="layui-form-label">版区名</label>
        <div class="layui-input-block">
            <input type="text" name="PlateForm[name]" lay-verify="required|name" autocomplete="off" value="" placeholder="请输入版区名" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">版区简介</label>
        <div class="layui-input-block">
            <input type="text" name="PlateForm[intro]" lay-verify="required|intro" autocomplete="off" value="" placeholder="请输入版区简介" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">版区图像</label>
        <div class="layui-input-block">
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="test1">上传图片</button>
                <div class="layui-upload-list layui-inline">
                    <img class="layui-upload-img" id="demo1" />
                    <p id="demoText"></p>
                    <input type="hidden" name="PlateForm[img]" value="" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">单选框</label>
        <div class="layui-input-block">
            <input type="radio" name="PlateForm[recommend]" value="2" title="推荐">
            <input type="radio" name="PlateForm[recommend]" value="1" title="不推荐" checked="">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="imagecretae">立即提交</button>
        </div>
    </div>
</form>
<script src="/static/js/jquery-3.3.1.min.js"></script>
<script src="/static/layui.js"></script>

<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
    layui.use(['form','upload'], function(){
        var form = layui.form;
        var $ = layui.jquery
            ,upload = layui.upload;

        var uploadInst = upload.render({
            elem: '#test1'
            ,url:"<?= Url::toRoute(['image/upload']) ?>"
            ,data:{_csrf:s,type:'plate'}
            ,field:"ImageForm[imageFile]"
            ,accept:'images'
            ,acceptMime:'image/jpeg,image/gif'
            ,exts:'jpg|png|gif|jpeg'
            ,size:300
            ,before: function(obj){
                obj.preview(function(index, file, result){
                    $('#demo1').attr('src', result);
                });
            }
            ,done: function(res){
                if(res.code > 0){return layer.msg('上传失败');}
                $("input[name='PlateForm[img]']").val(res.url);
                $('#demoText').html('上传成功');
            }
            ,error: function(){
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });
        form.on('submit(imagecretae)', function(data){
            $.ajax({
                type:'POST',
                async: false,
                url: "<?= Url::toRoute(['plate/create']) ?>",
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