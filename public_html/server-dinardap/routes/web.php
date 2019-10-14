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
   $router->post('checkSoap', ['uses' => 'DinardapController@checkSoap']);
   $router->post('cedula', ['uses' => 'DinardapController@Cedula']);
   $router->post('paquete', ['uses' => 'DinardapController@paquete']);
   $router->post('ruc', ['uses' => 'DinardapController@RUC']);
   $router->post('supercias', ['uses' => 'DinardapController@super_cias']);
});

$router->group(['middleware' => ['auth']], function () use ($router) {
   $router->post('/user/password_change', ['uses' => 'AuthController@passwordChange']);

   //DINARDAP

   //CRUD Ruc
   $router->post('/ruc_data', ['uses' => 'RucController@post']);
   $router->get('/ruc_data', ['uses' => 'RucController@get']);
   $router->get('/ruc_data/paginate', ['uses' => 'RucController@paginate']);
   $router->get('/ruc_data/backup', ['uses' => 'RucController@backup']);
   $router->put('/ruc_data', ['uses' => 'RucController@put']);
   $router->delete('/ruc_data', ['uses' => 'RucController@delete']);
   $router->post('/ruc_data/masive_load', ['uses' => 'RucController@masiveLoad']);

   //CRUD Identification
   $router->post('/identification', ['uses' => 'IdentificationController@post']);
   $router->get('/identification', ['uses' => 'IdentificationController@get']);
   $router->get('/identification/paginate', ['uses' => 'IdentificationController@paginate']);
   $router->get('/identification/backup', ['uses' => 'IdentificationController@backup']);
   $router->put('/identification', ['uses' => 'IdentificationController@put']);
   $router->delete('/identification', ['uses' => 'IdentificationController@delete']);
   $router->post('/identification/masive_load', ['uses' => 'IdentificationController@masiveLoad']);
});
