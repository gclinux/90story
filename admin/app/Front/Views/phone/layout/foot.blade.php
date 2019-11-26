<div class="foot">
<p>2019 © 所有内容版权归版权方或原作者所有 <br>
All contents are copyrighted by their respective owners or authors.<br>
本站提供免费小说阅读,并不存储实际内容,仅通过网络访问并转码成适合手机阅读的格式<br>偶尔出现空白和第三方小说广告,纯属上游问题导致现象。
<p>
<p>
	<a class="external" href="https://www.90text.com">免费小说</a>
	@if(isset($links))
		@foreach($links as $link)
		|<a class="external"  href="{{$link->link}}" alt="{{$link->alt}}">{{$link->name}}</a>
		@endforeach
	@endif
    @section('tool')
</p>
</div>