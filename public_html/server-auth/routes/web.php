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
   $router->post('/login', ['uses' => 'AuthController@login']);
   $router->post('/register', ['uses' => 'AuthController@register']);
   $router->post('/password_recovery_request', ['uses' => 'AuthController@passwordRecoveryRequest']);
   $router->get('/password_recovery', ['uses' => 'AuthController@passwordRecovery']);
   $router->get('/get_accounts', ['uses' => 'UserController@get_accounts']);
   $router->post('/block_account', ['uses' => 'UserController@block_account']);
   $router->post('/save_account', ['uses' => 'UserController@save_account']);
   $router->post('/password_reset_account', ['uses' => 'UserController@password_reset_account']);
   $router->post('/mass_upload', ['uses' => 'UserController@mass_upload']);
});

$router->group(['middleware' => ['auth']], function () use ($router) {
   $router->post('/user/password_change', ['uses' => 'AuthController@passwordChange']);

   //AUTH

   //CRUD ProfilePicture
   $router->post('/profilepicture', ['uses' => 'ProfilePictureController@post']);
   $router->get('/profilepicture', ['uses' => 'ProfilePictureController@get']);
   $router->get('/profilepicture/paginate', ['uses' => 'ProfilePictureController@paginate']);
   $router->put('/profilepicture', ['uses' => 'ProfilePictureController@put']);
   $router->delete('/profilepicture', ['uses' => 'ProfilePictureController@delete']);

   //CRUD User
   $router->post('/user', ['uses' => 'UserController@post']);
   $router->get('/user', ['uses' => 'UserController@get']);
   $router->get('/user/paginate', ['uses' => 'UserController@paginate']);
   $router->put('/user', ['uses' => 'UserController@put']);
   $router->delete('/user', ['uses' => 'UserController@delete']);
   $router->post('/user/create_account_by_rol', ['uses' => 'UserController@createAccountByRol']);
   $router->post('/user/register_user_establishment', ['uses' => 'UserController@register_user_establishment']);
   $router->put('/user/update_account_by_rol', ['uses' => 'UserController@updateAccountByRol']);
   $router->put('/user/update_user_establishment', ['uses' => 'UserController@update_user_establishment']);
   $router->get('/user/filtered_by_rol', ['uses' => 'UserController@filteredByRol']);
   $router->get('/user/get_by_rol', ['uses' => 'UserController@getByRol']);
   $router->delete('/user/delete_account_by_rol', ['uses' => 'UserController@deleteAccountByRol']);
   
    //CRUD AccountRol
   $router->post('/accountrol', ['uses' => 'AccountRolController@post']);
   $router->get('/accountrol', ['uses' => 'AccountRolController@get']);
   $router->get('/accountrol/filtered', ['uses' => 'AccountRolController@filtered']);
   $router->get('/accountrol/paginate', ['uses' => 'AccountRolController@paginate']);
   $router->get('/accountrol/backup', ['uses' => 'AccountRolController@backup']);
   $router->put('/accountrol', ['uses' => 'AccountRolController@put']);
   $router->delete('/accountrol', ['uses' => 'AccountRolController@delete']);
   $router->post('/accountrol/masive_load', ['uses' => 'AccountRolController@masiveLoad']);

   //CRUD AuthLocation
   $router->post('/authlocation', ['uses' => 'AuthLocationController@post']);
   $router->get('/authlocation', ['uses' => 'AuthLocationController@get']);
   $router->get('/authlocation/paginate', ['uses' => 'AuthLocationController@paginate']);
   $router->get('/authlocation/backup', ['uses' => 'AuthLocationController@backup']);
   $router->put('/authlocation', ['uses' => 'AuthLocationController@put']);
   $router->delete('/authlocation', ['uses' => 'AuthLocationController@delete']);
   $router->post('/authlocation/masive_load', ['uses' => 'AuthLocationController@masiveLoad']);
   
   //CRUD AccountRolAssigment
   $router->post('/accountrolassigment', ['uses' => 'AccountRolAssigmentController@post']);
   $router->get('/accountrolassigment', ['uses' => 'AccountRolAssigmentController@get']);
   $router->get('/accountrolassigment/paginate', ['uses' => 'AccountRolAssigmentController@paginate']);
   $router->get('/accountrolassigment/backup', ['uses' => 'AccountRolAssigmentController@backup']);
   $router->put('/accountrolassigment', ['uses' => 'AccountRolAssigmentController@put']);
   $router->delete('/accountrolassigment', ['uses' => 'AccountRolAssigmentController@delete']);
   $router->post('/accountrolassigment/masive_load', ['uses' => 'AccountRolAssigmentController@masiveLoad']);

   //CRUD Log
   $router->post('/log', ['uses' => 'LogController@post']);
   $router->get('/log', ['uses' => 'LogController@get']);
   $router->get('/log/paginate', ['uses' => 'LogController@paginate']);
   $router->get('/log/backup', ['uses' => 'LogController@backup']);
   $router->put('/log', ['uses' => 'LogController@put']);
   $router->delete('/log', ['uses' => 'LogController@delete']);
   $router->post('/log/masive_load', ['uses' => 'LogController@masiveLoad']);
});
