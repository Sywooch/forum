var url='index.php?r=';
layui.use(['element','table','form'], function(){
    var element = layui.element;
    var table = layui.table;
    var form=layui.form;
    //方法级渲染
    table.render({
        elem: '#user_table'
        ,url: url+'user/list'
        ,cols: [[
            {checkbox: true, fixed: true}
            ,{field:'id',title:'编号',width:80,sort:true,fixed:true}
            ,{field:'email',title:'邮箱',width:230}
            ,{field:'username', title:'昵称',width:200, sort: true}
            ,{field:'city', title: '所在地', width:120, sort: true}
            ,{field:'sex', title: '性别', width:80}
            ,{field:'level', title: '等级', width:80, sort: true}
            ,{field:'experience', title: '经验', sort: true, width:80}
            ,{field:'integral', title: '积分', sort: true, width:80}
            ,{field:'groups', title: '用户组', sort: true, width:80}
            ,{field:'status', title: '状态', width:80}
            ,{field:'ip', title: 'IP', sort: true, width:135}
            ,{field:'created_at', title: '注册时间',sort:true,width:150}
            ,{fixed:'right',title:'操作',align:'center',toolbar:'#userBar'}
        ]]
        ,id: 'userReload'
        ,page:true
        ,height:'full-200'
        ,text:'暂无数据'
        ,where:{_csrf:s}
        ,method:'post'
    });

    //方法级渲染 帖子列表
    table.render({
        elem: '#post_table'
        ,url: url+'post/list'
        ,cols: [[
            {checkbox: true, fixed: true}
            ,{field:'id',title:'编号',width:80,sort:true,fixed:true}
            ,{field:'username',title:'发布人',width:150}
            ,{field:'name', title:'所属版区',width:100}
            ,{field:'title', title: '标题', width:350}
            ,{field:'view', title: '查阅量',sort: true, width:80}
            ,{field:'comments', title: '评论数', width:80, sort: true}
            ,{field:'collection', title: '收藏量', sort: true, width:80}
            ,{field:'star', title: '点赞量', sort: true, width:80}
            ,{field:'essence', title: '是否精贴',width:100}
            ,{field:'is_hot', title: '是否热帖', width:100}
            ,{field:'create_at', title: '发布时间',sort:true,width:180}
            ,{fixed:'right',title:'操作',align:'center',toolbar:'#postBar'}
        ]]
        ,id: 'postReload'
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
        } else if(obj.event === 'del'){
            layer.confirm('真的要删除吗?', function(index){
                $.post(url+'user/del',{id:data.id,_csrf:s},function(res){
                    layer.msg(res.info);
                    if(res.code==1){
                        obj.del();
                        layer.close(index);
                    }
                });
            });
        } else if(obj.event === 'edit'){
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

    //监听post工具条
    table.on('tool(post)', function(obj){
        var data = obj.data;
        if(obj.event==='essence'){
            layer.confirm('确定要设置精贴?', function(){
                $.post(url+'post/essence',{id:data.id,_csrf:s},function(res){
                    layer.msg(res.info);
                });
            });
        }else if(obj.event === 'del'){
            layer.confirm('真的要删除吗?', function(index){
                $.post(url+'post/del',{id:data.id,_csrf:s},function(res){
                    layer.msg(res.info);
                    if(res.code==1){
                        obj.del();
                        layer.close(index);
                    }
                });
            });
        }else if(obj.event === 'hot'){
            layer.confirm('确定要设置为热帖?', function(){
                $.post(url+'post/hot',{id:data.id,_csrf:s},function(res){
                    layer.msg(res.info);
                });
            });
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