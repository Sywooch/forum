<?php
?>

<div class="demoTable" style="margin-top:10px;">
    <button class="layui-btn" data-type="getCheckData">批量禁用</button>
    <div class="layui-inline">
        <input class="layui-input" name="name" id="name" value="" autocomplete="off" placeholder="邮箱/昵称">
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
</div>

<table class="layui-hide" id="user_table" lay-filter="user"></table>

<script type="text/html" id="userBar">
    <!--<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>-->
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<!--<script type="text/html" id="switchSex">
    <input type="checkbox" name="sex" value="{{d.sex}}" lay-skin="switch" lay-text="女|男" lay-filter="userSex" {{ d.sex == 1 ? 'checked' : '' }}>
</script>-->
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
</script>