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
   return 'Web Wervice Realizado con LSCodeGenerator';
});

$router->group(['middleware' => []], function () use ($router) {
});

$router->group(['middleware' => ['auth']], function () use ($router) {

   //CATASTRO

   //CRUD Register
   $router->post('/register', ['uses' => 'RegisterController@post']);
   $router->get('/register', ['uses' => 'RegisterController@get']);
   $router->get('/register/paginate', ['uses' => 'RegisterController@paginate']);
   $router->get('/register/backup', ['uses' => 'RegisterController@backup']);
   $router->get('/register/search_by_ruc', ['uses' => 'RegisterController@search_by_ruc']);
   $router->get('/register/search_filtered', ['uses' => 'RegisterController@search_filtered']);
   $router->get('/register/getActivities', ['uses' => 'RegisterController@getActivities']);
   $router->get('/register/get_by_register_code', ['uses' => 'RegisterController@get_by_register_code']);
   $router->put('/register', ['uses' => 'RegisterController@put']);
   $router->delete('/register', ['uses' => 'RegisterController@delete']);
   $router->post('/register/masive_load', ['uses' => 'RegisterController@masiveLoad']);
});

