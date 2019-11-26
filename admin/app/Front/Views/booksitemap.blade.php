<?php echo'<?xml version="1.0" encoding="UTF-8"?>'?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url>
      <loc>{{env('APP_URL')}}/book_{$book->id}</loc>
      <lastmod>{{date('Y-m-d',strtotime($book->updated_at))}}</lastmod>
      <changefreq>{{$book->status==1?'yearly':'daily'}}</changefreq>
      <priority>1.0</priority>
</url>
@foreach($cats as $cat)
<url>
      <loc>{{env('APP_URL')}}/book_{{$cat->book_id}}/{{$cat->id}}.html</loc>
      <lastmod>{{date('Y-m-d',strtotime($cat->updated_at))}}</lastmod>
      <changefreq>{{$cat->spider_status==1?'yearly':'daily'}}</changefreq>
      <priority>0.6</priority>
</url>
@endforeach
</urlset>
