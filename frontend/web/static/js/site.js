var url=window.location.host;
url="http://"+url+"/";
var report;
var tos=GetQueryString("to");
if(tos!=null && tos.toString().length>=1) {
    if($("#"+tos)[0]!=undefined){
        var t=$("#"+tos).offset().top;
        $(window).scrollTop(t);
    }
}
function changeCap(){
    $.ajax({
        url:url+"pass/captcha&refresh",
        dataType:'json',
        cache:false,
        success:function(data){
            $("#imgVerifyCode").attr('src',data.url);
        }
    });
}
function porders(values,f){
    if(f!=undefined){
        window.location.href=url+"index/index.html?o="+values+"&f="+f;
        return false;
    }
    window.location.href=url+"index/index.html?o="+values;
}
function piorders(values,id,s,f){
    if(s!=0&&f!=0){
        window.location.href=url+"post/index.html?o="+values+"&id="+id+"&s="+s+"&f="+f;
        return false;
    }
    if(s==0&&f!=0){
        window.location.href=url+"post/index.html?o="+values+"&id="+id+"&f="+f;
        return false;
    }
    if(s!=0&&f==0){
        window.location.href=url+"post/index.html?o="+values+"&id="+id+"&s="+s;
        return false;
    }
    window.location.href=url+"post/index.html?o="+values+"&id="+id;
}
function pit(values,id,s,f){

    if(s!=0&&f!=0){
        window.location.href=url+"post/index.html?t="+values+"&id="+id+"&s="+s+"&f="+f;
        return false;
    }
    if(s==0&&f!=0){
        window.location.href=url+"post/index.html?t="+values+"&id="+id+"&f="+f;
        return false;
    }
    if(s!=0&&f==0){
        window.location.href=url+"post/index.html?t="+values+"&id="+id+"&s="+s;
        return false;
    }
    window.location.href=url+"post/index.html?t="+values+"&id="+id;
}
$(document).ready(function(){
    $('.sign').click(function(){
       $.post(url+'sign/index.html',{_csrf:s},function(res){
           var status=(res.code)==1?'success':'warning';
           var incons=(res.code)==1?'<span uk-icon=\'icon: check\'></span>':'<span uk-icon=\'icon: close\'></span>';
           UIkit.notification({message:incons+res.msg,timeout:3000,status:status});
           if(res.code=='1'){
               var peo=$('.signpeo').attr('data-sign');
               peo++;
               $('.signpeo').replaceWith('<span class="uk-text-middle signpeo" data-sign="'+peo+'">'+peo+'人</span>');
           }
       });
    });
    $('.report').click(function(){
        var obj=$(this).attr('uk-data');
        if(obj==undefined){
            return false;
        }
        var reg=/^[0-9]+.?[0-9]*$/;
        if(!reg.test(obj)){
            return false;
        }
        report=obj;
        $.post(url+'report/check.html',{_csrf:s},function(res){
            if(res.code!='1'){
                UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>'+res.msg,timeout:3000,status:'warning'});
                return false;
            }
        });
    });
    $('.repsub').click(function(){
        var reportson=$('#repson').val();
        var reportcon=$('#repcon').val();
        if(reportson==undefined){
            UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>请选择举报原因!',timeout:3000,status:'warning'});
            return false;
        }
        if(reportson==''){
            UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>请选择举报原因!',timeout:3000,status:'warning'});
            return false;
        }
        var data={
            _csrf:s,
            id:report,
            rs:reportson
        };
        if(reportcon!=''){
            var reg=/^[\u4e00-\u9fa5]+$/;
            if (!reg.test(reportcon)) {
                UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>举报原因请填写中文!',timeout:3000,status:'warning'});
                return false;
            };
            if(reportcon.length>20){
                UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>请输入20字以内!',timeout:3000,status:'warning'});
                return false;
            }
            var data={
                _csrf:s,
                id:report,
                rs:reportson,
                cn:reportcon
            };
        }
        $.post(url+'report/report',data,function(res){
            var status=(res.code)==1?'success':'warning';
            var incons=(res.code)==1?'<span uk-icon=\'icon: check\'></span>':'<span uk-icon=\'icon: close\'></span>';
            UIkit.notification({message:incons+res.msg,timeout:3000,status:status});
            if(res.code=='1'){
                UIkit.modal('#modal-report').hide();
            }
        });
    });
    $('.comment').click(function(){
        var content=$('#comment').val();
        if(content==''){
            UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>请填写评论内容!',timeout:3000,status:'warning'});
            return false;
        }
        if(content.length>30){
            UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>评论内容不得多于30字!',timeout:3000,status:'warning'});
            return false;
        }
        var obj=$('#comment').attr('data');
        if(obj==undefined||obj==''){
            return false;
        }
        var reg = new RegExp("^[0-9]*$");
        if(!reg.test(obj)){
            return false;
        }
        //评论人 评论帖子  评论内容  评论类型
        $.post(url+'comment/create.html',{_csrf:s,o:obj,t:1,c:content},function(res){
            var status=(res.code)==1?'success':'warning';
            var incons=(res.code)==1?'<span uk-icon=\'icon: check\'></span>':'<span uk-icon=\'icon: close\'></span>';
            UIkit.notification({message:incons+res.msg,timeout:3000,status:status});
            if(res.code==1){
                $('#comment').val('');
                $('.cft').append(res.data.content);
            }
        });
    });
    $('.reply').click(function(){
        var obj=$(this).attr('data'); //评论编号
        if(obj==undefined||obj==''){
            return false;
        }
        var reg = new RegExp("^[0-9]*$");
        if(!reg.test(obj)){
            return false;
        }
        var content=$('#reply'+obj).val();
        if(content==''){
            UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>请填写评论内容!',timeout:3000,status:'warning'});
            return false;
        }
        if(content.length>30){
            UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>评论内容不得多于30字!',timeout:3000,status:'warning'});
            return false;
        }
        $.post(url+'comment/create.html',{_csrf:s,o:obj,t:2,c:content},function(res){
            var status=(res.code)==1?'success':'warning';
            var incons=(res.code)==1?'<span uk-icon=\'icon: check\'></span>':'<span uk-icon=\'icon: close\'></span>';
            UIkit.notification({message:incons+res.msg,timeout:3000,status:status});
            if(res.code==1){
                $('#reply'+res.data.id).val('');
                $('#toggle-usage-'+res.data.id).attr('hidden','hidden');
                $('.cft').prepend(res.data.content);
            }
        });
    });
    $('.prisub').click(function(){
        //提交 被私人编号  提交私信内容
        var obj=$(this).attr('data'); //评论编号
        if(obj==undefined||obj==''){
            return false;
        }
        var reg = new RegExp("^[0-9]*$");
        if(!reg.test(obj)){
            return false;
        }
        var content=$('#pricontent').val();
        if(content==''||content==undefined){
            content=$('#pricontent-'+obj).val();
            if(content==''){
                UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>请填写私信内容!',timeout:3000,status:'warning'});
                return false;
            }
        }
        if(content.length>30){
            UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>评论私信不得多于30字!',timeout:3000,status:'warning'});
            return false;
        }
        $.post(url+'private/send.html',{_csrf:s,id:obj,content:content},function(res){
            var status=(res.code)==1?'success':'warning';
            var incons=(res.code)==1?'<span uk-icon=\'icon: check\'></span>':'<span uk-icon=\'icon: close\'></span>';
            UIkit.notification({message:incons+res.msg,timeout:3000,status:status});
            if(res.code=='1'){
                $('#pricontent').val('');
                UIkit.modal('#modal-prive').hide();
                $('#pricontent-'+res.data).val('');
                UIkit.modal('#modal-prive-'+res.data).hide();
            }
        });
    });
});
function collection(id){
    if(id==undefined){
        UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>'+res.msg,timeout:3000,status:'warning'});
        return false;
    }
    $.post(url+'collection/collection.html',{_csrf:s,id:id},function(res){
        var status=(res.code)==1?'success':'warning';
        var incons=(res.code)==1?'<span uk-icon=\'icon: check\'></span>':'<span uk-icon=\'icon: close\'></span>';
        UIkit.notification({message:incons+res.msg,timeout:3000,status:status});
        if(res.code==1){
            $('.collection').replaceWith('<a uk-icon="star" class="uk-margin-left collection"  style="color:#1e87f0;" onclick="uncollection('+res.data+')"></a>');
            var collnum=$('.collnum').text();
            $('.collnum').text(parseInt(collnum)+1);
        }
    });
}
function uncollection(id){
    if(id==undefined){
        UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>'+res.msg,timeout:3000,status:'warning'});
        return false;
    }
    $.post(url+'collection/uncollect.html',{_csrf:s,id:id},function(res){
        var status=(res.code)==1?'success':'warning';
        var incons=(res.code)==1?'<span uk-icon=\'icon: check\'></span>':'<span uk-icon=\'icon: close\'></span>';
        UIkit.notification({message:incons+res.msg,timeout:3000,status:status});
        if(res.code==1){
            $('.collection').replaceWith('<a uk-icon="star" class="uk-margin-left collection" onclick="collection('+res.data+')"></a>');
            var collnum=$('.collnum').text();
            $('.collnum').text(parseInt(collnum)-1);
        }
    });
}
function star(id){
    if(id==undefined){
        UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>'+res.msg,timeout:3000,status:'warning'});
        return false;
    }
    $.post(url+'star/star.html',{_csrf:s,id:id},function(res){
        var status=(res.code)==1?'success':'warning';
        var incons=(res.code)==1?'<span uk-icon=\'icon: check\'></span>':'<span uk-icon=\'icon: close\'></span>';
        UIkit.notification({message:incons+res.msg,timeout:3000,status:status});
        if(res.code==1){
            $('.star').replaceWith('<a uk-icon="heart" class="uk-margin-left star"  style="color:#1e87f0;" onclick="unstar('+res.data+')"></a>');
            var starnum=$('.starnum').text();
            $('.starnum').text(parseInt(starnum)+1);
        }
    });
}
function unstar(id){
    if(id==undefined){
        UIkit.notification({message:'<span uk-icon=\'icon: close\'></span>'+res.msg,timeout:3000,status:'warning'});
        return false;
    }
    $.post(url+'star/unstar.html',{_csrf:s,id:id},function(res){
        var status=(res.code)==1?'success':'warning';
        var incons=(res.code)==1?'<span uk-icon=\'icon: check\'></span>':'<span uk-icon=\'icon: close\'></span>';
        UIkit.notification({message:incons+res.msg,timeout:3000,status:status});
        if(res.code==1){
            $('.star').replaceWith('<a uk-icon="heart" class="uk-margin-left star" onclick="star('+res.data+')"></a>');
            var starnum=$('.starnum').text();
            $('.starnum').text(parseInt(starnum)-1);
        }
    });
}
function to(obj,id,total){
    var reg=/^[0-9]+.?[0-9]*$/;
    if(obj==undefined||id==undefined||total==undefined||obj.value==undefined){return false;}
    if(obj==''||id==''||total==''||obj.value==''){return false;}
    if(!reg.test(obj.value)||!reg.test(id)||!reg.test(total)){return false;}
    var incons="<span uk-icon='icon: close'></span>";
    var msg='无此楼层';
    if(obj.value<1){UIkit.notification({message:incons+msg,timeout:3000,status:'warning'});return false;}
    if(obj.value>total){UIkit.notification({message:incons+msg,timeout:3000,status:'warning'});return false;}
    window.location.href=url+"post/detail.html?id="+id+"&to="+obj.value;
}

function GetQueryString(name){
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}

