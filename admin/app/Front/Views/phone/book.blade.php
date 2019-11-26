@extends('phone.layout.main_tool')
@section('title',$book->name.'-'.$book->author.'')
@section('content')
<div class="card">
    <div class="card-header"><a href="/book_{{$book->id}}" class="external">《{{$book->name}}》</a></div>
    <div class="card-content">
      <div class="list-block media-list">
        <ul>
          <li class="item-content">
            <div class="item-media">
              <img src="{{$book->img}}" alt="{{$book->name}}" width="80" height="100">
            </div>
            <div class="item-inner">
                <div class="item-subtitle"><span>作者: {{$book->author}}</span></div>
                <div class="item-subtitle"><span>分类: {{$book->class}}</span></div>
                <div class="item-subtitle"><span>状态: {{$book->status?'完结':'连载'}} , <span>字数: {{$book->char_num?$book->char_num:'未统计'}}</span></span></div>
                <div class="item-subtitle"><span>最后更新: {{$book->updated_at}}</span></div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="card-content">
      <div class="card-content-inner">
        <p class="color-gray">简介</p>
        <p>&nbsp;&nbsp;《{{$book->name}}》是由{{$book->author}}所写的小说.{{$book->des}}</p>
      </div>
    </div>
    
    <div class="card-footer">
    @if ($last_read)
    <p class="color-gray">您上次阅读到:<a class="external" href="/book_{{$book->id}}/{{$last_read->id}}.html">{{$last_read->name}}</a></p>
    @else
    <p class="color-gray">向下滑动开始阅读第一章</p>
    @endif 
    </div>

</div>    

<div class="card" data-catid="{{$firstCat->id}}">
    <div class="card-header">{{$firstCat->name}}</div>
    <div class="card-content">
      <div class="card-content-inner text-font-setting">
      	<p>本站免费小说{{$book->name}}的内容均来自网络,仅为您提供更优质的手机阅读体验和让本书得到更多的推广和宣传,本书作者{{$book->author}},如想支持作者,可以到正版小说站点付费阅读.<br>您看到的小说内容是实时从网络其他站点中转,并不加以编辑,因此会有一些文字广告内容,请不要轻易相信里面的微信群,公众号,qq群,支付账户等信息,他们都与作者无关,请勿被骗.<br>--------</p>
        {!!$firstCat->content?$firstCat->content->content:'本章仅为分割而建,请下滑继续观看'!!}   
      </div>
    </div>
    <div class="card-footer">
        <a href="javascript:;" class="link share external">分享</a>
        <a href="javascript:;" class="link bug external">报错</a>
    </div>
</div>
@endsection

@section('catalogs')
@include ('phone.catmenu')
@endsection

@section('script')
@include('phone.loadbook')
@endsection

@section('des',"《{$book->name}》是由{$book->author}所写的{$book->class}免费小说")