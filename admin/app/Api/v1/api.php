<?php
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
//use App\Api\v1\Middleware\CheckP;
/**
 * 路由,如果要修改"/api的前缀,请在Providers/RouteServiceProvider.php里修改"
 */
//Admin::routes();

Route::group([
    'prefix'        => '/v1',
   // 'middleware'    => [CheckP::class],
    'namespace'     =>'App\Api\v1\Controllers',
], function (Router $router) {
    //$router->apiResource('test', TestController::class);
    $router->get('test','TestController@index');

});