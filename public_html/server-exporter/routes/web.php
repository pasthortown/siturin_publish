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
   $router->post('download/excel_file', ['uses' => 'ExporterController@excel_file']);
   $router->post('download/pdf', ['uses' => 'ExporterController@pdf_file']);
   $router->post('download/template', ['uses' => 'ExporterController@pdf_template']);
   $router->post('download/pdf_checklist', ['uses' => 'ExporterController@pdf_checklist']);
   $router->post('download/pdf_tarifario_rack', ['uses' => 'ExporterController@pdf_tarifario_rack']);
   $router->post('download/pdf_declaration', ['uses' => 'ExporterController@pdf_declaration']);
});

$router->group(['middleware' => ['auth']], function () use ($router) {

   //EXPORTER

   //CRUD Template
   $router->post('/template', ['uses' => 'TemplateController@post']);
   $router->get('/template', ['uses' => 'TemplateController@get']);
   $router->get('/template/paginate', ['uses' => 'TemplateController@paginate']);
   $router->get('/template/backup', ['uses' => 'TemplateController@backup']);
   $router->put('/template', ['uses' => 'TemplateController@put']);
   $router->delete('/template', ['uses' => 'TemplateController@delete']);
   $router->post('/template/masive_load', ['uses' => 'TemplateController@masiveLoad']);

   //CRUD Document
   $router->post('/document', ['uses' => 'DocumentController@post']);
   $router->get('/document', ['uses' => 'DocumentController@get']);
   $router->get('/document/paginate', ['uses' => 'DocumentController@paginate']);
   $router->get('/document/backup', ['uses' => 'DocumentController@backup']);
   $router->put('/document', ['uses' => 'DocumentController@put']);
   $router->delete('/document', ['uses' => 'DocumentController@delete']);
   $router->post('/document/masive_load', ['uses' => 'DocumentController@masiveLoad']);
   $router->post('/document/id', ['uses' => 'DocumentController@get_doc_id']);
});
