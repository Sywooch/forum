<?php
use yii\helpers\Url;
$this->title=$title.'-论坛管理后台';
?>
<div class="userTable">
    <div class="layui-inline">
        <input class="layui-input" name="name" id="name" autocomplete="off" value="" placeholder="邮箱/昵称" />
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
</div>

<table class="layui-hide" id="LAY_table_user" lay-filter="LAY_table_user"></table>

<script type="text/html" id="toolbarUser">
    <div class="layui-btn-container">
        <?php if(Yii::$app->user->can('user/disable')){ ?>
        <button class="layui-btn layui-btn-sm" lay-event="getCheckData">批量禁止</button>
        <?php } ?>
    </div>
</script>

<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
    layui.use(['element','table'], function(){
        var table = layui.table;

        table.render({
             elem: '#LAY_table_user'
            ,url:"<?= Url::toRoute(['user/list'])?>"
            ,cols: [[
                {checkbox: true,fixed: true},
                {field:'email', title: '邮箱',width:180},
                {field:'username', title: '昵称'},
                {field:'city', title: '城市'},
                {field:'sex', title: '性别'},
                {field:'level', title: '等级',sort: true},
                {field:'experience', title: '经验值', sort: true},
                {field:'integral', title: '积分',sort: true},
                {field:'status', title: '状态'},
                {field:'view', title: '访客', sort: true},
                {field:'ip', title: 'IP'},
                {field:'created_at', title: '注册时间'}
            ]]
            ,id:'userReload'
            ,page: true
            ,height:'full-200'
            ,limit:20
            ,toolbar: '#toolbarUser'
            ,defaultToolbar: ['']
        });
        table.on('toolbar(LAY_table_user)', function(obj){
            var checkStatus = table.checkStatus(obj.config.id);
            switch(obj.event){
                case 'getCheckData':
                    var data = checkStatus.data;
                    if(data.length==0){layer.msg('请选择数据');return false;}
                    var idArr=[];
                    for(var i=0;i<data.length;++i){idArr[i]=data[i].id;}
                    $.post("<?= Url::toRoute(['user/disable'])?>",{id:idArr,_csrf:s},function(res){
                        res.code==1?layer.msg(res.info,{icon:5}):layer.msg(res.info,{icon:1,time:1000},function(){
                            window.location.reload();
                        });
                    });
                    break;
            };
        });

        var $ = layui.$, active = {
            reload: function(){
                var demoReload = $('#name');

                table.reload('userReload', {
                    page: {
                        curr:1
                    },
                    where: {
                        name:demoReload.val()
                    }
                });
            }
        };

        $('.userTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>



