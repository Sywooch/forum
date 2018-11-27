<?php
?>
<div class="demoTable" style="margin-top:10px;">
    <button class="layui-btn" data-type="getCheckData">批量审核</button>
    <div class="layui-inline">
        <input class="layui-input" name="name" id="name" value="" autocomplete="off" placeholder="发布人/标题">
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
</div>

<table class="layui-hide" id="post_table" lay-filter="post"></table>

<script type="text/html" id="postBar">
    <a class="layui-btn layui-btn-xs" lay-event="essence">精贴</a>
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="hot">热帖</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
</script>
