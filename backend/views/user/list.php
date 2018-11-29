<?php
use yii\helpers\Url;
$this->title=$title.'-论坛管理后台';
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
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
</script>
<script>
    var s="<?= \Yii::$app->request->csrfToken ?>";
    layui.use(['element','table','form'], function(){
        var element = layui.element;
        var table = layui.table;
        var form=layui.form;

        table.render({
            elem: '#user_table'
            ,url: "<?= Url::toRoute('user/list')  ?>"
            ,cols: [[
                 {checkbox: true, fixed: true}
                ,{field:'id',title:'编号',width:80,sort:true,fixed:true}
                ,{field:'email',title:'邮箱',width:175}
                ,{field:'username', title:'昵称'}
                ,{field:'city', title: '所在地'}
                ,{field:'sex', title: '性别'}
                ,{field:'level', title: '等级',sort: true}
                ,{field:'experience', title: '经验', sort: true}
                ,{field:'integral', title: '积分', sort: true}
                ,{field:'groups', title: '用户组'}
                ,{field:'status', title: '状态'}
                ,{field:'ip', title: 'IP'}
                ,{field:'created_at', title: '注册时间'}
                ,{fixed:'right',title:'操作',align:'center',toolbar:'#userBar',width:120}
            ]]
            ,id: 'userReload'
            ,page:true
            ,height:'full-200'
            ,text:'暂无数据'
            ,where:{_csrf:s}
            ,method:'post'
        });

        //监听性别操作
        form.on('switch(userSex)', function(obj){
            layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
        });

        //监听用户工具条
        table.on('tool(user)', function(obj){
            var data = obj.data;
            if(obj.event === 'detail'){
                layer.msg('ID：'+ data.id + ' 的查看操作');
            }else if(obj.event === 'edit'){
                layer.open({
                    type: 2,
                    title:'编辑',
                    area: ['580px','350px'],
                    shade: 0.8,
                    closeBtn: 1,
                    shadeClose: false,
                    content: url+'user/edit&id='+data.id
                });
                //layer.alert('编辑行：<br>'+ JSON.stringify(data))
            }
        });

        //帖子
        var $ = layui.$, active = {
            getCheckData: function(){ //获取选中数据
                var checkStatus = table.checkStatus('postReload')
                    ,data = checkStatus.data;
                if(data.length==0){
                    layer.msg('请选择数据');
                    return false;
                }
                var obj=new Array();
                for(var i=0;i<data.length;++i){
                    obj[i]=data[i].id;
                }
                $.post(url+'user/disable',{id:obj,_csrf:s},function(res){
                    layer.msg(res.info);
                });

                layer.alert(JSON.stringify(data));
            },
            reload: function(){
                var name = $('#name');
                //执行重载
                table.reload('postReload', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        name:name.val(),
                    }
                });
            }
        };

        var $ = layui.$, active = {
            getCheckData: function(){ //获取选中数据
                var checkStatuss = table.checkStatus('userReload')
                    ,data = checkStatuss.data;
                if(data.length==0){
                    layer.msg('请选择数据');
                    return false;
                }
                var obj=new Array();
                for(var i=0;i<data.length;++i){
                    obj[i]=data[i].id;
                }
                $.post(url+'user/disable',{id:obj,_csrf:s},function(res){
                    layer.msg(res.info);
                });

                layer.alert(JSON.stringify(data));
            },
            reload: function(){
                var name = $('#name');
                //执行重载
                table.reload('userReload', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        name:name.val(),
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