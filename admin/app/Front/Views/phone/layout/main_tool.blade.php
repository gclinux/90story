@extends('phone.layout.main')
@section('tool')
@if($show_title)
<!-- 标题栏 -->
<header class="bar bar-nav">
    <a class="icon icon-home pull-left external" href="/"></a>
    <a class="icon icon-refresh pull-right external" href="javascript:window.location.reload();"></a>
    <h1 class="title"> @yield('title')</h1>
</header>
@endif
    <!-- 标题栏 end -->
<!-- 二级工具栏 -->
<div class="font-setting bar bar-footer-secondary" style="display:none">
    <p class="text-font-setting center">预览文字,这个设置只会影响正文内容</p>
    <p class="buttons-row"><a href="javascript:;"  class="font-dec button external button-round">变小</a><a href="javascript:;" class="font-add button external button-round">变大</a></p>
</div>
<!-- 二级工具栏 end -->
<!-- 分享 -->
<div class="share-setting bar bar-footer-secondary" style="display:none">
    <div class="share-component" data-disabled="linkedin,tencent,google,twitter,facebook"></div>
    <div style="clear:both"></div>
</div>
<!-- 分享 end -->
<!-- 工具栏 -->
<nav class="bar bar-tab" id="book-main-tools">
    <a class="tab-item  menu-menu" href="#page-catalogs">
        <span class="icon bookfont book-menu"></span>
        <span class="tab-label">章节</span>
    </a>
    <a class="tab-item external menu-book" href="/">
        <span class="icon bookfont book-book"></span>
        <span class="tab-label">更多小说</span>
    </a>
    <a class="tab-item external menu-night" href="javascript:;">
        <span class="icon bookfont book-day"></span>
        <span class="tab-label">白天</span>
    </a>
    <a class="tab-item external menu-font" href="javascript:;">
        <span class="icon bookfont book-font"></span>
        <span class="tab-label">字体</span>
    </a>
    <a class="tab-item external menu-share" href="javascript:;">
        <span class="icon icon-share"></span>
        <span class="tab-label">分享</span>
    </a>
</nav>
@endsection

@section('other-page')
<div class="page" id='page-catalogs'>
    <!-- 标题栏 -->
    <header class="bar bar-nav">
        <a class="icon icon-left pull-left" href="#page-main"></a>
        <a class="icon icon-refresh pull-right"></a>
        <h1 class="title"> @yield('title') 的章节</h1>
    </header>
        <!-- 标题栏 end -->
    <!-- 工具栏 -->
    <nav class="bar bar-tab">
        <a class="tab-item  menu-back" href="#page-main">
            <span class="icon icon-left"></span>
            <span class="tab-label">返回</span>
        </a>
        <a class="tab-item external menu-up" href="javascript:;">
            <span class="icon icon-up"></span>
            <span class="tab-label">正序</span>
        </a>
    </nav>

    <!-- 这里是页面内容区 -->
    <div class="content"><div class="content-inner">
    @section('catalogs')
        这里是目录
    @show
    <div class="infinite-scroll-preloader">
        <div class="preloader"></div>
    </div>
    </div></div>
</div>
@endsection