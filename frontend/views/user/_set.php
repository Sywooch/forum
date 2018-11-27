<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<link rel="stylesheet" href="/static/css/layui.css"  media="all">

<div class="uk-flex">
    <div class="uk-width-4-5">
        <form class="uk-form-stacked" action="<?= Url::toRoute(['user/update','id'=>$posts['id']])?>" method="post" >
            <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" />
            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">用户名</label>
                <div class="uk-form-controls uk-width-1-2">
                    <input class="uk-input" name="UpdateForm[username]" id="form-stacked-text" type="text" placeholder="用户名" value="<?= $posts['username']?Html::encode($posts['username']):'' ?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">个人签名</label>
                <div class="uk-form-controls uk-width-1-2">
                    <input class="uk-input" name="UpdateForm[intro]" id="form-stacked-text" type="text" placeholder="个人签名" value="<?= $posts['intro']?Html::encode($posts['intro']):'' ?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">城市</label>
                <div class="uk-form-controls uk-width-1-2">
                    <input class="uk-input" name="UpdateForm[city]" id="form-stacked-text" type="text" placeholder="城市" value="<?= $posts['city']?Html::encode($posts['city']):'' ?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">邮箱</label>
                <div class="uk-form-controls uk-width-1-2">
                    <input class="uk-input" name="UpdateForm[email]" id="form-stacked-text" type="text" placeholder="邮箱" value="<?= Html::encode($posts['email']) ?>" disabled>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-select">性别</label>
                <div class="uk-form-controls uk-width-1-2">
                    <select class="uk-select" name="UpdateForm[sex]" id="form-stacked-select">
                        <option <?php if($posts['sex']=='1'){ ?>  selected <?php } ?> value="1">男</option>
                        <option <?php if($posts['sex']=='2'){ ?>  selected <?php } ?> value="2" >女</option>
                    </select>
                </div>
            </div>

            <div class="uk-margin">
                <button type="submit" class="uk-button uk-button-default uk-width-1-2">提交修改</button>
            </div>

        </form>
    </div>
    <div class="uk-width-1-5 uk-text-center">
        <div class="layui-upload">
            <div class="layui-upload-list">
                <img class="uk-comment-avatar" id="avatar" src="<?= $posts['avatar'] ?>" width="90" height="90" />
            </div>
            <button type="button" class="layui-btn" id="avatarUp">上传图片</button>
            <p id="errText"></p>
        </div>
    </div>
</div>
<script src="/static/layui.js" charset="utf-8"></script>
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
    layui.use('upload', function(){
        var $ = layui.jquery
            ,upload = layui.upload;
        var uploadInst = upload.render({
             elem: '#avatarUp'
            ,url:"<?= Url::toRoute(['image/upload']) ?>"
            ,field:'ImageForm[imageFile]'
            ,data:{_csrf:s,type:'avatar'}
            ,accept:'images'
            ,exts:'jpg|png|gif|jpeg'
            ,size:300
            ,number:1
            ,before: function(obj){
                obj.preview(function(index, file, result){
                    $('#avatar').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                if(res.code > 0){
                    $('#errText').html('<span style="color: #FF5722;">上传失败!<br/>'+res.msg+'</span>');
                    return false;
                }
                $('#errText').html('<span style="color: #FF5722;">上传成功</span>');
            }
            ,error: function(){
                var errText = $('#errText');
                errText.html('<span style="color: #FF5722;">上传失败</span>');
            }
        });
    });
</script>