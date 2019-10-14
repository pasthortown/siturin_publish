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
   $router->post('registers', ['uses' => 'ConsultorController@registers']);
   $router->get('register_by_code', ['uses' => 'ConsultorController@registerByCode']);
   $router->get('get_registers_assigned_inspector_id', ['uses' => 'ConsultorController@get_registers_assigned_inspector_id']);
   $router->get('get_registers_assigned_financial_id', ['uses' => 'ConsultorController@get_registers_assigned_financial_id']);   
});

$router->group(['middleware' => ['auth']], function () use ($router) {
   
});
