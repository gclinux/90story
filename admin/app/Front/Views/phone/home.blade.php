@extends('phone.layout.main')
@section('title','90文字网')
@section('tool')
  @include('phone.layout.searchbox');
@endsection
@section('css')
<style>
* { touch-action: pan-y; } 
</style>
@endsection
@section('content')

<div class="card">
    @if($last_read)
    <div class="card-header">最近阅读<small class="color-gray">连续阅读两章以上</small></div>
    <div class="card-content">
      <div id="read-history" class="card-content-inner">
        <div class="wrapper wrapper02" id="wrapper1">
          <div class="scroller">
            <ul class="clearfix">
              @foreach($last_read as $bok)
              <li data-href="/book_{{$bok['id']}}"><a href="/book_{{$bok['id']}}" class="external"><img alt="您阅读过的:{{$bok['name']}}" src="{{$bok['img']}}"><span>{{$bok['name']}}</span></a></li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
    @endif
    @if($banner)
    <div class="card-content">
      <a href="{{$banner[0]['target_url']}}" external ><img alt="banner" class="banner" src="{{$banner[0]['img']}}"></a>
    </div>
    @endif
</div>
<!-- 推荐-->
@if($good_book)
<?php 
$good_book_1 = array_slice($good_book,0,5);
$good_book_2 = array_slice($good_book,5);
?>
<div class="card">
  <div class="card-header">编辑推荐<span class="push-right"><a class="external" href="/introductions"><small>更多</small></a></span></div>
  <div class="card-content"> 
      
        <div class="list-block media-list">
          <ul>
            @foreach($good_book_1 as $gb)
            <li class="item-content" >
              <div class="item-media">
              <a class="external" href="/book_{{$gb['book_id']}}">
                <img alt="推荐好书:{{$gb['book']['name']}} 作者{{$gb['book']['author']}}" src="{{$gb['book']['img']}}" class="book-img">
              </a>
              </div>
              <div class="item-inner">
                  <div class="item-subtitle"><a class="external" href="/book_{{$gb['book_id']}}">{{$gb['book']['name']}}</a></div>
                  <div><p class="word">小编说:{{$gb['reason']}}</p></div> 
                  <div><?php
                    $tags = explode(',',$gb['tag']);
                    foreach($tags AS $tag){
                      echo "<a class=\"label\">$tag</a>";
                    }
                  ?></div>
              </div>
            </li>
            @endforeach
          </ul>
        </div>
    </div>
  @if(count($good_book_2)>0)
  <div class="card-content">
    <div class="wrapper02 wrapper" id="wrapper2">
      <div class="scroller">
        <ul class="clearfix">
            @foreach($good_book_2 as $i=>$gb)
              @if ($i > 0)
              <li><a href="/book_{{$gb['book_id']}}" title="{{$gb['book']['name']}}" alt="{{$gb['book']['name']}}" class="external"><img alt="好书推荐:{{$gb['book']['name']}} 作者:$gb['book']['author']" class="book-img" src="{{$gb['book']['img']}}"><span>{{$gb['book']['name']}}</span></a></li>
              @endif
            @endforeach
        </ul>
      </div>
    </div>
  </div>
  @endif
</div>
@endif
<!-- 推荐end -->
<!-- 热门小说-->
@if($topBook)
<div class="card">
  <div class="card-header">热门小说<a class="external" href="/top"><small>更多</small></a></div>
  <div class="card-content">
  <div class="list-block media-list">
    <ul>
      @foreach($topBook as $i=>$book)
        @if($i<9)
          <li class="item-content" >
            <div class="item-media">
            <a href="/book_{{$book->id}}"  class="external"><img alt="热门小说-书名:{{$book->name}},作者:{{$book->author}}" src="{{$book->img}}" class="book-img"></a>
            </div>
            
            <div class="item-inner">
                <div class="item-subtitle"><a href="/book_{{$book->id}}"  class="external">{{$book->name}}</a></div>
                <div class="item-subtitle"><span>作者: {{$book->author}}</span></div>
                <div class="item-subtitle"><span>分类: {{$book->class}}</span></div>
                <div class="item-subtitle"><span>状态: {{$book->status?'完结':'连载'}} , <span>字数: {{$book->char_num?$book->char_num:'未统计'}}</span></span></div>
                <div class="item-subtitle"><span>最后更新: {{$book->updated_at}}</span></div>
            </div>
          </li>
        @else
        <li class="item-content" >
          <a class="row item-inner external" href="/book_{{$book->id}}">
              <div class="col-60">{{$book->name}}</div>
              <div class="col-40"><small>{{$book->author}}</small></div>
          </a>
        </li>
        @endif
      @endforeach
      </ul>
  </div>
  </div>
</div>
@endif
<!-- 热门小说 end-->

<div class="card">
  <div class="card-header">最近更新<a class="external" href="/last-update"><small>更多</small></a></div>
  <div class="card-content">
    <div class="list-block media-list">
      <ul>
          @foreach($lastUpdate as $i=>$cat)
          <li class="item-content" data-href="/book_{{$cat->book->id}}/{{$cat->id}}.html">
            <a class="row item-inner external"  href="/book_{{$cat->book->id}}/{{$cat->id}}.html">
                <div class="col-60">{{$cat->name}}</div>
                <div class="col-40"><small>{{$cat->book->name}}</small></div>
            </a>
          </li>
          @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection


@section('script')
<script src="https://cdn.bootcss.com/iScroll/5.2.0/iscroll.min.js"></script>
<script src="/js/front/phone/navbarscroll.js?v={{$static_ver}}"></script>
<script>
  //$('.wrapper').navbarscroll();
  $('.infinite-scroll-preloader').remove();
  $.toast("提示:点击图片进入小说");
</script>
@endsection

@section('link')
@include('phone.layout.link')
@endsection