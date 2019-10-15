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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/tes','TemplateController@tes');

$router->group(['prefix'=>'checklists'],function($router){
    $router->group(['prefix'=>'templates'],function($router){
        $router->get('/', 'TemplateController@list');
        $router->post('/', 'TemplateController@store');
        $router->get('/{id}', 'TemplateController@show');
        $router->patch('/{id}','TemplateController@update');
        $router->delete('/{id}','TemplateController@destroy');
        $router->post('/{id}/assigns', 'TemplateController@assigns');
    });
});