$(function(){
    var theme = getCookie('theme');
    if(theme){
        setTheme(theme);
    }
    var fontsize = getCookie('fontsize');
    if(fontsize){
        console.log(fontsize);
        $(".text-font-setting").css("font-size", fontsize+'px');
    }
    $('.menu-night').click(function(){
        $('body').removeClass('theme-dark').removeClass('theme-keepeye');
        if($(this).find('.tab-label').text()=='白天'){
            setTheme('theme-dark');
        }else if($(this).find('.tab-label').text()=='夜间'){
            setTheme('theme-keepeye');
        }else{
            setTheme('');
        }
    });
    $('.menu-font').click(function(){
        $('.share-setting').hide();
        $('.font-setting').fadeToggle();
    });
    var config={}
    $('.menu-share').click(function(){
        Mshare.popup(config);
    });
    $('.content').click(function(){
        $('.font-setting').hide();
        $('.share-setting').hide();
    })
    $(document).on('click','[data-href]',function(obj){
        url = $(this).data('href');
        console.log(url);
        window.location.href=url;
    })
    $(document).on('click','.link.bug',function(){
        var cat_id = $(this).parent().parent().data('catid');
        $.getJSON('/pushbug_'+cat_id,function(data){
            $.toast("上报成功,感谢您.我们会尽快修复.");
            //$.toast("感谢您的支持.");
        });
    })
    $(document).on('click','.link.share',function(){
        $('.font-setting').hide();
        $('.share-setting').show();
    })
    $(".font-add").click(function () {
        var s_size = $(".text-font-setting").css("font-size"),s_number = parseFloat(s_size);
        if(s_number<60)
        {
             var newSize = s_number +2;
             setCookie('fontsize',newSize);
            $(".text-font-setting").css("font-size", newSize);
        }else{
            $.toast("已经最大");
        }
        return false;
    });
    $(".font-dec").click(function () {
        var s_size = $(".text-font-setting").css("font-size"),s_number = parseFloat(s_size);
        if(s_number>12)
        {
            var newSize = s_number -2;
            setCookie('fontsize',newSize);
            $(".text-font-setting").css("font-size", newSize);
        }else{
            $.toast("已经最小");
        }
        return false;
    });
     
    $('.social-share a').addClass('external');//防止分享出现ajax

    $('.menu-up').click(function(){
        $('#book-catalogs-list li.item-content').each(function(i,ele){
            $('#book-catalogs-list').prepend(ele.outerHTML);
            $(ele).remove();
            var t = $('.menu-up .tab-label').text();
            $('.menu-up .tab-label').text(t=='倒序'?'正序':'倒序');
            //console.log(111);
        })
    })
    $('#header-serach-btn').click(function(){
        $('#search-form').submit();
    })
    
})
//设置cookies
function setCookie(name,value)
{
    var Days = 30;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
//读取cookies
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
    return unescape(arr[2]);
    else
    return null;
}
function setTheme(theme){
    $('body').removeClass('theme-dark').removeClass('theme-keepeye');
    if(theme == 'theme-dark'){
        $('.menu-night').find('.tab-label').text('夜间');
        $('.menu-night').find('.book-day').removeClass('book-day').addClass('book-night');
        setCookie('theme','theme-dark');
        $('body').addClass('theme-dark');
    }else if(theme == 'theme-keepeye'){
        $('.menu-night').find('.book-night').removeClass('book-night').addClass('book-keepeye');
        $('.menu-night').find('.tab-label').text('护眼');
        $('body').addClass('theme-keepeye');
        setCookie('theme','theme-keepeye');
    }else{
        $('.menu-night').find('.book-keepeye').removeClass('book-keepeye').addClass('book-day');
        $('.menu-night').find('.tab-label').text('白天');
        setCookie('theme','');
    }

}