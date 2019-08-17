<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

Route::group([
  'middleware' => ['auth'],
  'prefix'=>'data/v1'
],function(){

    // DataController
    Route::get('/','DataController@RecordList');
    Route::get('/{tableName}','DataController@RecordList');
    Route::get('/{tableName}/{idOrKey}','DataController@GetRecord');

    Route::delete('/{tableName}/{idOrKey}','DataController@DeleteRecord');

    Route::post('/{tableName}', 'DataController@InsertRecord');


});

