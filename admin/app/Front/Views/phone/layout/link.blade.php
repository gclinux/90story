<p>
	<a class="external" href="http://www.90text.com">免费小说</a>
	@if(isset($links))
		@foreach($links as $link)
		|<a class="external"  href="{{$link->link}}" alt="{{$link->alt}}">{{$link->name}}</a>
		@endforeach
	@endif
    @section('tool')
</p>
