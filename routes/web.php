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

Route::group([
  'middleware' => ['auth'],
  'prefix'=>'data/v1'
],function(){
  Route::get('/','QueryController@RecordList');
  Route::get('/{tableName}','QueryController@RecordList');
  Route::get('/{tableName}/{idOrKey}','QueryController@GetRecord');

  Route::delete('/{tableName}/{idOrKey}','QueryController@DeleteRecord');
}); 

