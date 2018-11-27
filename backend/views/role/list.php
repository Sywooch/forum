<?php
?>
<div class="demoTable">
    <button class="layui-btn" data-type="delRole">批量删除</button>
    <button class="layui-btn" data-type="add">新增</button>

    <div class="layui-inline">
        <input class="layui-input" name="id" id="name" autocomplete="off" placeholder="角色名">
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
</div>

<table class="layui-hide" id="LAY_table_role" lay-filter="role"></table>

<script type="text/html" id="barRole">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看成员</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">分配权限</a>
</script>

<script>
    var s="<?= Yii::$app->request->csrfToken ?>";
    layui.use('table', function(){
        var table = layui.table;

        //方法级渲染
        table.render({
            elem: '#LAY_table_role'
            ,url: 'index.php?r=role/list'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'description', title: '角色名'}
                ,{fixed:'right',title:'操作',width:178, align:'center', toolbar: '#barRole'}
            ]]
            ,id: 'roleReload'
            ,page: true
            ,height: 315
            ,where:{_csrf:s}
            ,method:'post'
        });
        //监听工具条
        table.on('tool(role)', function(obj){
            var data = obj.data;
            if(obj.event === 'detail'){



                layer.msg('ID：'+ data.id + ' 的查看操作');
            }else if(obj.event === 'edit'){
                layer.alert('编辑行：<br>'+ JSON.stringify(data))
            }
        });


        var $ = layui.$, active = {
            delRole: function(){ //获取选中数据
                var checkStatus = table.checkStatus('roleReload')
                    ,data = checkStatus.data;
                if(data.length==0){
                    layer.msg('请选择数据');
                    return false;
                }
                var obj=new Array();
                for(var i=0;i<data.length;++i){
                    obj[i]=data[i].name;
                }
                $.post('index.php?r=role/del',{id:obj,_csrf:s},function(res){
                    layer.msg(res.info);
                });

                layer.alert(JSON.stringify(data));
            }
            ,add: function(){ //获取选中数目
                var checkStatus = table.checkStatus('roleReload')
                    ,data = checkStatus.data;
                layer.msg('选中了：'+ data.length + ' 个');
            },
            reload: function(){
                var name = $('#name');

                //执行重载
                table.reload('roleReload', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        name: name.val(),
                    }
                });
            }
        };

        $('.demoTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>

