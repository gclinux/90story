@extends('phone.layout.main')
<?php
$url = '"/introductions-more/"+last_id';
?>
@section('title','小编推荐')
@section('tool')
@include('phone.layout.searchbox')
@endsection
@section('content')
@if($count>0)
  @foreach($books as $book)
  <div class="card">
    <div class="card-header"><a href="/book_{{$book['book_id']}}" class="external">《{{$book['book']['name']}}》</a></div>
    <div class="card-content">
      <div class="list-block media-list">
        <ul>
          <li class="item-content">
            <div class="item-media">
            <a href="/book_{{$book['book_id']}}" class="external"><img src="{{$book['book']['img']}}" title="{{$book['book']['name']}}" class="book-img"></a>
            </div>
            <div class="item-inner">
                <div class="item-subtitle"><span>作者: {{$book['book']['author']}}</span></div>
                <div class="item-subtitle"><span>分类: {{$book['book']['class']}}</span></div>
                <div class="item-subtitle"><span>状态: {{$book['book']['status']?'完结':'连载'}} , <span>字数: {{$book['book']['char_num']?$book['book']['char_num']:'未统计'}}</span></span></div>
                <div class="item-subtitle"><span>最后更新: {{$book['book']['updated_at']}}</span></div>
            </div>
          </li>
        </ul>
      </div>
    </div>
   
    <div class="card-content">
      <div class="card-content-inner">
        <p class="color-gray">小编说</p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;{{$book['reason']}}</p>
      </div>
    </div>

    <div class="card-content">
      <div class="card-content-inner">
        <p class="color-gray">简介</p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;{{$book['book']['des']}}</p>
      </div>
    </div>

  </div>
  @endforeach
@else
<p>无内容</p>
@endif
@endsection

@section('script')
<script>
var limit = {{$limit}};
var count = {{$count}};
var last_id = {{$last_id}}
$(document).ready(function(){
  function checkLimit(){
    if(limit>count){
      $('.infinite-scroll-preloader').remove();
      $.detachInfiniteScroll($('.infinite-scroll'));
    }
  }
  checkLimit();
  var loading = false;
  var maxItems = 30;// 最多可加载的条目
  function addItems(data) {
      if(!data){
          $.detachInfiniteScroll($('.infinite-scroll'));
          $('.infinite-scroll-preloader').text('无更多内容');
          return;
      }

      var cards = $('#book-content .card')
      var s_size = $(".text-font-setting").css("font-size");
      if(cards.length >= maxItems){
          cards.eq(0).remove();//删除最前面的那个
      }
      var books = data.books;
      last_id = data.last_id;
      for(let i in books){
        var book=books[i].book;
        var reason = books[i].reason
        var html = '\
        <div class="card">\
            <div class="card-header"><a href="/book_'+book.id+'" class="external">《'+book.name+'》</a></div>\
            <div class="card-content">\
              <div class="list-block media-list">\
                <ul>\
                  <li class="item-content">\
                    <div class="item-media">\
                    <a href="/book_'+book.id+'" class="external"><img src="'+book.img+'" title="'+book.name+'" class="book-img"></a>\
                    </div>\
                    <div class="item-inner">\
                        <div class="item-subtitle"><span>作者: '+book.author+'</span></div>\
                        <div class="item-subtitle"><span>分类: '+book.class+'</span></div>\
                        <div class="item-subtitle"><span>状态:  '+(book.status?'完结':'连载')+' , <span>字数: '+(book['char_num']?book['char_num']:'未统计')+'</span></span></div>\
                        <div class="item-subtitle"><span>最后更新: '+book['updated_at']+'</span></div>\
                    </div>\
                  </li>\
                </ul>\
              </div>\
            </div>\
            <div class="card-content">\
              <div class="card-content-inner">\
                <p class="color-gray">小编评论</p>\
                <p>&nbsp;&nbsp;&nbsp;&nbsp;'+reason+'</p>\
              </div>\
            </div>\
            <div class="card-content">\
              <div class="card-content-inner">\
                <p class="color-gray">简介</p>\
                <p>&nbsp;&nbsp;&nbsp;&nbsp;'+book['des']+'</p>\
              </div>\
            </div>\
        </div>';
      //console.log(html);
      $('.infinite-scroll-preloader').before(html);
      
    }
    count = data.books.length;
    checkLimit();
  }
    // 注册'infinite'事件处理函数
  $(document).on('infinite', '.infinite-scroll',function() {
        // 如果正在加载，则退出
        if (loading) return;
        // 设置flag
        loading = true;
        let url ={!!$url!!};
        //console.log(url);
        $.getJSON(url,function(data){
          //console.log(data);
          addItems(data);
          loading = false;
        })
  });
// $.attachInfiniteScroll('.infinite-scroll');
});
</script>
@endsection