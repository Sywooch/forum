<?php
$this->title='控制台首页-BBS论坛';
?>

<blockquote class="layui-elem-quote">项目仅作展示用，不用于任何商业用途!</blockquote>

<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
    layui.use(['element','table'], function(){
        var table = layui.table;
        var element = layui.element
            ,$ = layui.jquery;
    });
</script>
