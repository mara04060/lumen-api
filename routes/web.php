<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//$router->get('/', function () use ($router) {
//    return $router->app->version();
//});


$router->group(['middleware' => 'auth', 'prefix' => 'api'], function () use ($router) {
    $router->post('/product',  ['as' => 'product', 'uses' => 'Controller@product']);
});


// Bad status
$router->group([], function () use ($router) {

    $controller = 'Controller@error';

    $router->get('/{route:.*}/', $controller);
    $router->post('/{route:.*}/', $controller);
    $router->put('/{route:.*}/', $controller);
    $router->patch('/{route:.*}/', $controller);
    $router->delete('/{route:.*}/', $controller);

});

