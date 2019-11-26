<div class="list-block">    
    <ul class="list-container " id="book-catalogs-list">
        @foreach($cats as $cat)
            <li href="/book_{{$cat->book_id}}/{{$cat->id}}.html" class="item-content"><div class="item-inner"><div class="item-title" ><a id="cat-{{$cat->id}}" class="external" title="{{$cat->name}}-{$book->name}}" data-inx="{{$cat->inx}}" data-num="{{$cat->num}}" href="/book_{{$cat->book_id}}/{{$cat->id}}.html">{{$cat->name}}</a></div></div></li>
        @endforeach
    </ul>
</div>