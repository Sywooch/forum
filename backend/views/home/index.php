<?php
$this->title='控制台首页-BBS论坛';
?>
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
    layui.use(['element','table'], function(){
        var table = layui.table;
        var element = layui.element
            ,$ = layui.jquery;
    });
</script>
