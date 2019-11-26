<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/home', 'HomeController@index')->name('admin.home');
    $router->redirect('/', '/admin/books',301);
    $router->resource('books', BookController::class);
    $router->resource('proxy', ProxyController::class);
    
    $router->get('/spider_ip/{max_page?}','ProxyController@spiderIp');
    $router->get('/api/books/getBooks','BookController@getBooks');

    $router->get('/submit-baidu-today','LinkController@submitBaiduToday');
    $router->get('/submit-baidu','LinkController@submitBaidu');
    $router->get('/seo-update','LinkController@seoUpdate');
    
    $router->resource('catalogs', CatalogController::class);
    $router->resource('good-books', GoodController::class);
    $router->resource('axd', AxdController::class);//叫ad容易被一些拦截软件拦截..
    //$router->resource('contents', ContentController::class);
    $router->resource('search-histories', SearchHistoryController::class);
    $router->resource('book-sources', BookSourceController::class);
    $router->resource('links', LinkController::class);
    $router->resource('seo-logs', SeoLogController::class);
});
