<?php
//use Illuminate\Http\Request;
use Illuminate\Routing\Router;
Route::get('sitemap.xml', 'SitemapController@index');
Route::get('site-map.xml', 'SitemapController@index');
Route::get('/booksite_{book_id}.xml', 'SitemapController@book');
Route::get('/', 'HomeController@index');
Route::get('test', 'TestController@index');
Route::get('ua', 'TestController@getUa');
Route::get('book_{book_id}/{cat_id}.html', 'BookController@catalog');
Route::get('book_{book_id}', 'BookController@index');
Route::get('pushbug_{cat_id}', 'BookController@catBug');
Route::get('nextcat_{book_id}_{inx}_{num}', 'BookController@nextCat');
Route::get('next_{book_id}_{inx}_{num}', 'BookController@cataNext');
Route::get('search', 'SearchController@show')->middleware('throttle:10,3');
Route::get('search-more/{keyword}/{last_id}', 'SearchController@ajax');
Route::get('introductions', 'IntroductionsController@show');
Route::get('introductions-more/{page}', 'IntroductionsController@ajax');
Route::get('top', 'TopController@show');
Route::get('top-more/{page}', 'TopController@ajax');
Route::get('last-update', 'LastUpdateController@show');
Route::get('last-update-more/{page}', 'LastUpdateController@ajax');
Route::get('class.html', 'ClassController@index');
Route::get('class/{keyword}', 'ClassController@show');
Route::get('class-more/{keyword}/{last_id}', 'ClassController@ajax');
Route::get('server', 'ToolsController@server');
Route::get('del-luoxia', 'ToolsController@deleteLuoxiaLink');


// Route::group(['prefix'=>'search','middleware'=>'throttle:10,3'],function($r){
//     $r->get('/?keyword={keyword}','SearchController@search');
// });