@extends('phone.layout.main')
@section('title','小说分类-90文字网')
@section('tool')
  @include('phone.layout.searchbox');
@endsection

@section('content')
<div class="content-padded grid"><div class="card"><div class="card-content">
    <div class="row">
      @foreach($classes as $class)
      <div class="col-33" data-href="/class/{{urlencode($class->class)}}">
        <div><a title="{{$class->class}}类小说" alt="{{$class->class}}类小说" href="/class/{{urlencode($class->class)}}"><img alt="小说分类:{{$class->class}} 点击进去查看所有{{$class->class}}免费小说" class="book-img" src="{{$class->img}}"></a></div>
        <div class="book-class-name">{{$class->class}}</div>
      </div>
     @endforeach
    </div>
</div></div></div>
@endsection


@section('script')
<script>
  //$('.wrapper').navbarscroll();
  $('.infinite-scroll-preloader').remove();
</script>
@endsection