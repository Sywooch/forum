<?php
use yii\helpers\Url;
$this->title=$title.'-论坛管理后台';
?>

<div class="postTable">
    <div class="layui-inline">
        <input class="layui-input" name="name" id="name" value="" autocomplete="off" placeholder="发布人/标题">
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
</div>

<table class="layui-hide" id="postTable" lay-filter="postTable"></table>

<script type="text/html" id="postBar">
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="hot">热帖</a>
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="essence">精贴</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
    layui.use(['element','table'], function(){
        var table = layui.table;
        var element = layui.element
            ,$ = layui.jquery;

        table.render({
            elem: '#postTable'
            ,url:"<?= Url::toRoute(['post/list']) ?>"
            ,title: '用户数据表'
            ,cols: [[
                 {field:'username',title:'发布人',width:150}
                ,{field:'name', title:'所属版区'}
                ,{field:'title', title: '标题',width:300}
                ,{field:'view', title: '查阅量',sort: true}
                ,{field:'comments', title: '评论数',sort: true}
                ,{field:'collection', title: '收藏量'}
                ,{field:'star', title: '点赞量', sort: true}
                ,{field:'essence', title: '是否精贴'}
                ,{field:'is_hot', title: '是否热帖'}
                ,{field:'create_at', title: '发布时间',sort:true,width:170}
                ,{fixed:'right',title:'操作',align:'center',toolbar:'#postBar',width:210}
            ]]
            ,page: true
            ,id: 'postReload'
            ,height:'full-200'
            ,where:{_csrf:s}
            ,method:'post'
            ,limit:20
            ,limits:[20,40,60,80,100]
        });

        table.on('tool(postTable)', function(obj){
            var data = obj.data;
            var id=data.id;
            if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    $.post("<?= Url::toRoute(['post/delete']) ?>",{id:id,_csrf:s},function(res){
                        res.code==1?layer.msg(res.info,{icon:5}):layer.msg(res.info,{icon:1,time:1000},function(){
                            obj.del();
                        });
                    });
                    layer.close(index);
                });
            } else if(obj.event === 'edit'){
                var index=layer.open({
                    type: 2,
                    area: ['700px','750px'],
                    title:'编辑',
                    fixed: false,
                    maxmin:true,
                    content:"/post/update?id="+id,
                });
                layer.full(index);
            }else if(obj.event === 'hot'){
                layer.confirm('确定要设置为热帖?', function(){
                    $.post("<?= Url::toRoute(['post/hot']) ?>",{id:data.id,_csrf:s},function(res){
                        res.code==1?layer.msg(res.info,{icon:5}):layer.msg(res.info,{icon:1,time:1000},function(){
                            window.location.reload();
                        });
                    });
                });
            }else if(obj.event === 'essence'){
                layer.confirm('确定要设置为精帖?', function(){
                    $.post("<?= Url::toRoute(['post/essence']) ?>",{id:data.id,_csrf:s},function(res){
                        res.code==1?layer.msg(res.info,{icon:5}):layer.msg(res.info,{icon:1,time:1000},function(){
                            window.location.reload();
                        });
                    });
                });
            }
        });

        var $ = layui.$, active = {
            reload: function(){
                var name = $('#name');
                table.reload('postReload', {
                    page: {
                        curr: 1
                    }
                    ,where: {
                        name:name.val(),
                    }
                });
            }
        };

        $('.postTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>