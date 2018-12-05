<?php
use yii\helpers\Url;
$this->title=$title.'-论坛管理后台';
?>
<div class="adminTable">
    <div class="layui-inline">
        <input class="layui-input" name="names" id="names" autocomplete="off" placeholder="权限名称">
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
    <?php if(Yii::$app->user->can('permission/create')){ ?>
    <button class="layui-btn" data-type="add">添加</button>
    <?php } ?>
</div>
<table class="layui-hide" id="LAY_table_admin" lay-filter="admin"></table>

<script type="text/html" id="barAdmin">
    <?php if(Yii::$app->user->can('permission/update')){ ?>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <?php } ?>
    <?php if(Yii::$app->user->can('permission/delete')){ ?>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    <?php } ?>
</script>

<script>
    var s="<?= \Yii::$app->request->csrfToken?>";
    layui.use(['element','table'], function(){
        var element = layui.element;
        var table = layui.table;

        table.on('tool(admin)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                layer.confirm('确定要删除么？', function(index){
                    $.ajax({
                        type:'POST',
                        async: false,
                        url: "<?= Url::toRoute('permission/delete') ?>",
                        data: {id:data.id,_csrf:s},
                        success: function (result) {
                            layer.msg(result.info,{time:1000});
                            if(result.code==0){
                                obj.del();
                            }
                        }
                    });
                    layer.close(index);
                });
            } else if(obj.event === 'edit'){
                var id=data.id;
                layer.open({
                    type: 2,
                    area: ['700px','300px'],
                    title:'编辑',
                    fixed: false,
                    maxmin:true,
                    content:"/permission/update?id="+id,
                });
            }
        });

        table.render({
            elem: '#LAY_table_admin'
            ,url: "<?= Url::toRoute(['permission/list']) ?>"
            ,cols: [[
                 {field:'name',title:'权限名'}
                ,{field:'description', title: '权限描述',templet: function(d){
                         if(d.fid==0){
                             return '<span style="color:#5FB878;">'+d.description+'</span>';
                         }else{
                             return d.description;
                         }
                     }}
                ,{field:'created_at', title:'创建时间'}
                ,{field:'updated_at', title:'编辑时间'}
                ,{fixed:'right','title':'操作',width:200, align:'center',toolbar: '#barAdmin'}
            ]]
            ,id: 'table_admin'
            ,cellMinWidth: 80
            ,height:'full-200'
            ,page:true
            ,limit:100
            ,limits:[100,200,300]
            ,method:'post'
            ,where:{_csrf:s}
        });

        var $ = layui.$, active = {
            add:function(){
                layer.open({
                    type: 2,
                    area: ['750px','300px'],
                    title:'添加',
                    fixed: false,
                    maxmin:true,
                    content:"<?= Url::toRoute(['permission/create']) ?>",
                });
            },
            reload: function(){
                var names = $('#names');
                table.reload('table_admin', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        names:names.val()
                    }
                });
            }
        };
        $('.adminTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>

