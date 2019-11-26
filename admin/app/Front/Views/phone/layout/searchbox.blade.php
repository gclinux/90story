<!-- 标题栏 -->
<header class="bar bar-nav">
    <a class="icon icon-home pull-left external" href="/"></a>
    <a class="icon icon-refresh pull-right external" href="javascript:window.location.reload();"></a>
    <h1 class="title"> @yield('title')</h1>
</header>
<!-- 标题栏 end -->
<header class="bar bar-header-secondary">
    <form id="search-form" action="/search" method="get">
    <div class="searchbar row">
      <div class="col-25 book-class">
        <a class="button button-fill button-dark external" href="/class.html"><span class="icon icon-app"></span>分类</a>
      </div>
      <div class="search-input col-60">
        <label class="icon icon-search" for="search"></label>
        <input type="search" name="keyword" id='search' value="{{isset($keyword)?$keyword:''}}" placeholder='输入您想看的书名...'/>
      </div>
      <a id="header-serach-btn" class="button button-fill button-primary col-15 button-search "><span class="icon icon-search"></span></a>
    </div>
    </form>
</header>