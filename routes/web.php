<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

//$router->group(['prefix' => 'api'], function ($router) {
//
//    $router->group(['prefix' => 'hotels'], function ($router) {
//        $router->get('/', 'Api\HotelsController@ingex');
//        $router->get('/{id}', 'Api\HotelsController@one');
//    });
//
//});

$router->group(['prefix' => 'api/hotels',], function () use ($router) {

    $router->get('/', 'Api\HotelsController@index');
    $router->post('/store', 'Api\HotelsController@store');
});
$router->get('/{route:.*}/', function ()  {
    return view('app');
});

