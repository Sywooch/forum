<?php
use yii\helpers\Url;
$this->title=$title.'-论坛管理后台';
?>
<div class="plateTable">
    <div class="layui-inline">
        <input class="layui-input" name="name" id="name" value="" autocomplete="off" placeholder="版区名">
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
</div>

<table class="layui-hide" id="plateTable" lay-filter="plateTable"></table>

<script type="text/html" id="plateBar">
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="hot">热帖</a>
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="essence">精贴</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script src="/static/layui.js"></script>
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
    layui.use(['element','table'], function(){
        var table = layui.table;
        var element = layui.element
            ,$ = layui.jquery;

        table.render({
            elem: '#plateTable'
            ,url:"<?= Url::toRoute(['plate/list']) ?>"
            ,cols: [[
                 {field:'name',title:'版区名',width:150,templet: function(d){
                         if(d.fid==0){return '<span style="color:#5FB878;">'+d.name+'</span>' }else{return d.name;}
                     }}
                ,{field:'intro', title:'版区介绍'}
                ,{field:'img', title:'版区图标',align:'center',templet:function(res){
                        return res.img==undefined||res.img==''?'':'<img src="'+res.img+'" onclick="albumLayer(this)" style="width:35px;height:35px;">';
                    }}
                ,{field:'totals', title: '版区总贴数',width:300}
                ,{field:'is_recommend', title: '是否推荐',sort: true}
                ,{field:'create_at', title: '发布时间',sort:true,width:170}
                ,{fixed:'right',title:'操作',align:'center',toolbar:'#plateBar',width:210}
            ]]
            ,page: true
            ,id: 'plateReload'
            ,height:'full-200'
            ,where:{_csrf:s}
            ,method:'post'
        });

        table.on('tool(plateTable)', function(obj){
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
                table.reload('plateReload', {
                    page: {
                        curr: 1
                    }
                    ,where: {
                        name:name.val(),
                    }
                });
            }
        };

        $('.plateTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
    function albumLayer(obj){
        var img=obj.src;
        layer.open({
            type:1,
            title: false,
            closeBtn:1,
            area:'350px',
            skin:'layui-layer-nobg',
            shadeClose: true,
            content:"<img src='"+img+"' style='width:350px;'/>"
        });
    }
</script>
