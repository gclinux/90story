<script>
    var read_info={"book":"{{$firstCat->book_id}}","cat":"{{$firstCat->cat_id}}","inx":"{{$firstCat->inx}}","num":"{{$firstCat->num}}" }
    var loading = false;
    var is_iphone = {{$is_iphone}};
    var maxItems = 20;// 最多可加载的条目
    var title = $(document).attr("title"); 
    function addItems(dd) {
            if((!dd) ||(!dd.cat)){
                $.detachInfiniteScroll($('.infinite-scroll'));
                $('.infinite-scroll-preloader').text('无更多内容');
                return;
            }
            let data = dd.cat;
            let axd = dd.axd;
           // $.toast(data.name);
            var cards = $('#book-content .card')
            var s_size = $(".text-font-setting").css("font-size");
           
            var html = '\
            <div class="card" data-catid='+data.id+'"">\
                <div class="card-header">'+data.name+'</div>\
                <div class="card-content">\
                <div class="card-content-inner text-font-setting">\
                    '+(data.content?data.content.content:'')+'\
                </div>\
                </div>\
                <div class="card-content"><a class="external" href="'+axd.target_url+'"><img class="banner" src="'+axd.img+'"></a></div>\
                <div class="card-footer">\
                    <a href="javasript:;" class="link external share">分享</a>\
                    <a href="javasript:;" class="link external bug">报错</a>\
                </div>\
            </div>';
            //console.log(html);
            $('.infinite-scroll-preloader').before(html);
            $(".text-font-setting").css("font-size",s_size);
            if(cards.length >= maxItems){
                if(is_iphone){
                    lastIndex();
                }else{
                    cards.eq(0).remove();//删除最前面的那个
                }
            }
            read_info.book = data.book_id;
            read_info.cat = data.id;
            read_info.inx = data.inx;
            read_info.num = data.num;
            var stateObject = {};
            var tit = data.name + title;
            //console.log(tit);
            var newUrl = "/book_"+ read_info.book+'/'+ read_info.cat+'.html';
            history.pushState(stateObject,tit,newUrl);
            $('.item-title a.active').removeClass('active');
            $('#cat-'+data.id).addClass('active');
    }

    
      // 注册'infinite'事件处理函数
    $(document).on('infinite', '.infinite-scroll',function() {
          // 如果正在加载，则退出
          if (loading) return;
          // 设置flag
          loading = true;
          let url = '/nextcat_'+read_info.book+'_'+read_info.inx+'_'+read_info.num;
          //console.log(url);
          $.getJSON(url,function(data){
            addItems(data);
            loading = false;
          })
         
    });
   // $.attachInfiniteScroll('.infinite-scroll');
function lastIndex(){    
    var html = '\
    <div class="card">\
    <div class="card-header">您已经连续读了20章了,建议您让眼睛休息一下,十分钟后再继续读</div>\
    <div class="card-content">\
        <div class="list-block">\
            <ul>\
            <li>\
                <a external href="/next_'+read_info.book+'_'+read_info.inx+'_'+read_info.num+'" class="item-link item-content">\
                <div class="item-media"><i class="icon  bookfont book-keepeye"></i></div>\
                <div class="item-inner">\
                    <div class="item-title">休息完点击这里继续阅读下一章</div>\
                </div>\
                </a>\
            </li>\
            </ul>\
        </div>\
    </div>\
    </div>';

    $('.infinite-scroll-preloader').before(html);
    $.detachInfiniteScroll($('.infinite-scroll'));
    $('.infinite-scroll-preloader').remove();
    
}
</script>