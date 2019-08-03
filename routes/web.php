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

  // 'middleware' => 'api',
  'prefix' => 'auth'

], function ($router) {
  Route::post('register', 'AuthController@register');
  Route::post('login', 'AuthController@login');
  Route::post('logout', 'AuthController@logout');
  Route::post('refresh', 'AuthController@refresh');
  Route::post('me', 'AuthController@me');

});

// ['middleware' => 'jwt.auth'], 
//     function() use ($router) {


Route::group([
  'prefix' => 'data'
],function($router){
 
  Route::get('{tableName}','QueryController@getObject', function ($select){
    // return $tableName;
  });

  Route::get('{tableName}/{idOrKey}','QueryController@getObject', function (){
    // return $tableName;
  }); 

   // Route::get('getObject/tableName/{tableName}','QueryController@getObject');
  // Route::get('getObject/tableName/{tableName}','QueryController@getObject');
  /* Route::get('get','QueryController@get');
  Route::get('get','QueryController@get');
  Route::get('get','QueryController@get'); */

});

