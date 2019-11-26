<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8">
    <title> @yield('title','首页')-90文字网-免费小说_最优质的手机小说网,比笔趣阁更好</title>
    <meta name="keywords" content="免费小说,手机小说,笔趣阁,热门小说,小说推荐,@yield('title')@yield('keyword')">
    <meta name="Description" content=" @section('des')90文字网是一个供最着重手机阅读体验的免费小说网站,用心精选提供各类优质小说、热门小说,无广告,比笔趣阁更好@show">
    <link rel="stylesheet" href="https://cdn.bootcss.com/light7/0.4.3/css/light7.min.css">
    <link rel="stylesheet" href="/css/font_icon/iconfont.css?v={{$static_ver}}">
    <link rel="stylesheet" href="/css/front/phone/app.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/social-share.js/1.0.16/css/share.min.css">
    @yield('css','')
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <base target="_self" href="/" />
</head>
<body>
    <div class="page-group">
        <!-- 单个page ,第一个.page默认被展示-->
        <div class="page page-current" id='page-main'>
            @yield('tool')
            <!-- 这里是页面内容区 -->
            <div id="book-content" class="content infinite-scroll" data-distance="410">
                @section('content')
                    这是文章内容
                @show
                <!-- preloader -->
                <div class="infinite-scroll-preloader">
                    <div class="preloader"></div>
                </div>
                @include('phone.layout.foot')
            </div>
        </div>
        @yield('other-page')
        <!-- popup, panel 等放在这里 -->
        <div class="panel-overlay"></div>
    </div>
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
    <script type='text/javascript' src='https://cdn.bootcss.com/light7/0.4.3/js/light7.min.js' charset='utf-8'></script>
    <script src="/js/m-share/dist/m-share.min.js?v={{$static_ver}}"></script>
    <script type='text/javascript' src="/js/front/phone/app.js?v={{$static_ver}}" charset='utf-8'></script>
    <!-- 默认必须要执行$.init(),实际业务里一般不会在HTML文档里执行，通常是在业务页面代码的最后执行 -->
    
    @yield('script')
    <script >
        $.init();
    </script>
    @include('baidu')
</body>
</html>