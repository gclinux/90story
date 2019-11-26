@extends('phone.layout.main_tool')
@section('title',$firstCat->book->name.'-'.$firstCat->book->author)
@section('content')
@if ($last_read and $last_read->id != $firstCat->id)
<div class="card">
    <div class="card-content">
        <div class="card-content-inner">
                <div class="item-inner">
                    <div class="item-title"><span class="color-gray">您上次阅读到:</span>{{$last_read->name}}</div>
                </div>
            </a>
        </div>
    </div>
    <div class="card-footer"><a class="external" title="您上次阅读,点击可以继续观看" href="/book_{{$last_read->book_id}}/{{$last_read->id}}.html">点击继续上次阅读</a></div>
</div>
@elseif(!$last_read)
<div class="card">
    <div class="card-content">
        <div class="card-content-inner">
                <div class="item-inner">
                    <div class="item-title"><span class="color-gray">您首次阅读本书,建议从:</span>第一章开始</div>
                </div>
            </a>
        </div>
    </div>
    <div class="card-footer"><a title="免费阅读第一章" alt="免费阅读第一章" class="external" href="/book_{{$firstCat->book->id}}">点击进入第一章</a></div>
</div>
@endif
<div class="card" data-catid="{{$firstCat->id}}">
    <div class="card-header">{{$firstCat->name}}</div>
    <div class="card-content">
      <div class="card-content-inner text-font-setting">
          {!!$firstCat->content?$firstCat->content->content:'<p></p>'!!}
          @if($firstCat->url == 'noData')
          <p>本章是作为一个大主题的开始,没有实际内容,请继续下拉观看内容</p>
          <p></p>
          @endif
      </div>
    </div>
    <div class="card-content"><img class="banner" src="/images/banner.jpg"></div>
    <div class="card-footer">
        <a href="javascript:;" class="link external share">分享</a>
        <a href="javascript:;" class="link external bug">报错</a>
    </div>
</div>
@endsection

@section('catalogs')
@include ('phone.catmenu')
@endsection

@section('script')
@include('phone.loadbook')
@endsection
<?php $book=$firstCat->book; ?>
@section('des',"{$firstCat->name}《{$book->name}》是由{$book->author}所写的{$book->class}免费小说")