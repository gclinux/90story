<?php echo'<?xml version="1.0" encoding="UTF-8"?>'?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<sitemap>
      <loc>{{env('APP_URL')}}/</loc>
      <lastmod>{{date('Y-m-d', time()-600)}}</lastmod>
</sitemap>
@foreach($books as $book)
<sitemap>
      <loc>{{env('APP_URL')}}/booksite_{{$book->id}}.xml</loc>
      <lastmod>{{date('Y-m-d',strtotime($book->updated_at))}}</lastmod>
</sitemap>
@endforeach
</sitemapindex>
