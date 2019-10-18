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
    /**
     * TEMPLATES
     */
    $router->group(['prefix'=>'templates'],function($router){
        $router->get('/', 'TemplateController@list');
        $router->post('/', 'TemplateController@store');
        $router->get('/{id}', 'TemplateController@show');
        $router->patch('/{id}','TemplateController@update');
        $router->delete('/{id}','TemplateController@destroy');
        $router->post('/{id}/assigns', 'TemplateController@assigns');
    });

    /**
     * HISTORY
     */
    $router->get('/histories','HistoryController@list');
    $router->get('/histories/{historyId}','HistoryController@show');

    /**
     * ITEMS
     */
    $router->post('/complete','ItemController@complete');
    $router->post('/incomplete','ItemController@incomplete');
    $router->get('/{checklistId}/items','ItemController@getItems');
    $router->post('/{checklistId}/items','ItemController@storeItems');
    $router->get('/{checklistId}/items/{itemId}','ItemController@getChecklistItem');
    $router->patch('/{checklistId}/items/{itemId}','ItemController@updateChecklistItem');
    $router->delete('/{checklistId}/items/{itemId}','ItemController@destroyChecklistItem');
    $router->post('{checklistId}/items/_bulk','ItemController@bulk');
    $router->get('/items/summaries','ItemController@summaries');
    $router->get('/items','ItemController@items');

    /**
     * CHECKLISTS
     */
    $router->get('/{checklistId}','ChecklistController@show');
    $router->patch('/{checklistId}','ChecklistController@update');
    $router->delete('/{checklistId}','ChecklistController@destroy');
    $router->post('/','ChecklistController@store');
    $router->get('/','ChecklistController@list');



});


