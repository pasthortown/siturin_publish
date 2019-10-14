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
   //CRUD Zonal
   $router->get('/zonal', ['uses' => 'ZonalDataController@get']);
});

$router->group(['middleware' => ['auth']], function () use ($router) {
   //CRUD Ruc
   $router->post('/ruc', ['uses' => 'RucController@post']);
   $router->get('/ruc', ['uses' => 'RucController@get']);
   $router->get('/ruc/get_id_contact_ruc', ['uses' => 'RucController@get_id_contact_ruc']);
   $router->get('/ruc/get_by_ruc_number', ['uses' => 'RucController@get_by_ruc_number']);
   $router->post('/ruc/register_ruc', ['uses' => 'RucController@register_ruc']);
   $router->get('/ruc/paginate', ['uses' => 'RucController@paginate']);
   $router->get('/ruc/filtered', ['uses' => 'RucController@filtered']);
   $router->get('/ruc/backup', ['uses' => 'RucController@backup']);
   $router->put('/ruc/update_ruc', ['uses' => 'RucController@update_ruc']);
   $router->put('/ruc', ['uses' => 'RucController@put']);
   $router->delete('/ruc', ['uses' => 'RucController@delete']);
   $router->post('/ruc/masive_load', ['uses' => 'RucController@masiveLoad']);

   //CRUD TaxPayerType
   $router->post('/taxpayertype', ['uses' => 'TaxPayerTypeController@post']);
   $router->get('/taxpayertype', ['uses' => 'TaxPayerTypeController@get']);
   $router->get('/taxpayertype/paginate', ['uses' => 'TaxPayerTypeController@paginate']);
   $router->get('/taxpayertype/backup', ['uses' => 'TaxPayerTypeController@backup']);
   $router->put('/taxpayertype', ['uses' => 'TaxPayerTypeController@put']);
   $router->delete('/taxpayertype', ['uses' => 'TaxPayerTypeController@delete']);
   $router->post('/taxpayertype/masive_load', ['uses' => 'TaxPayerTypeController@masiveLoad']);

   //CRUD Establishment
   $router->post('/establishment', ['uses' => 'EstablishmentController@post']);
   $router->post('/establishment/register_establishment_data', ['uses' => 'EstablishmentController@register_establishment_data']);
   $router->get('/establishment', ['uses' => 'EstablishmentController@get']);
   $router->get('/establishment/get_by_ruc', ['uses' => 'EstablishmentController@getByRuc']);
   $router->get('/establishment/paginate', ['uses' => 'EstablishmentController@paginate']);
   $router->get('/establishment/backup', ['uses' => 'EstablishmentController@backup']);
   $router->get('/establishment/filtered', ['uses' => 'EstablishmentController@filtered']);
   $router->post('/establishment/set_register_date', ['uses' => 'EstablishmentController@set_register_date']);
   $router->put('/establishment', ['uses' => 'EstablishmentController@put']);
   $router->delete('/establishment', ['uses' => 'EstablishmentController@delete']);
   $router->post('/establishment/masive_load', ['uses' => 'EstablishmentController@masiveLoad']);

   //CRUD PersonRepresentative
   $router->post('/personrepresentative', ['uses' => 'PersonRepresentativeController@post']);
   $router->get('/personrepresentative', ['uses' => 'PersonRepresentativeController@get']);
   $router->get('/personrepresentative/paginate', ['uses' => 'PersonRepresentativeController@paginate']);
   $router->get('/personrepresentative/backup', ['uses' => 'PersonRepresentativeController@backup']);
   $router->put('/personrepresentative', ['uses' => 'PersonRepresentativeController@put']);
   $router->delete('/personrepresentative', ['uses' => 'PersonRepresentativeController@delete']);
   $router->post('/personrepresentative/masive_load', ['uses' => 'PersonRepresentativeController@masiveLoad']);

   //CRUD PersonRepresentativeAttachment
   $router->post('/personrepresentativeattachment', ['uses' => 'PersonRepresentativeAttachmentController@post']);
   $router->get('/personrepresentativeattachment', ['uses' => 'PersonRepresentativeAttachmentController@get']);
   $router->get('/personrepresentativeattachment/paginate', ['uses' => 'PersonRepresentativeAttachmentController@paginate']);
   $router->get('/personrepresentativeattachment/backup', ['uses' => 'PersonRepresentativeAttachmentController@backup']);
   $router->get('/personrepresentativeattachment/filtered', ['uses' => 'PersonRepresentativeAttachmentController@filtered']);
   $router->put('/personrepresentativeattachment', ['uses' => 'PersonRepresentativeAttachmentController@put']);
   $router->delete('/personrepresentativeattachment', ['uses' => 'PersonRepresentativeAttachmentController@delete']);
   $router->post('/personrepresentativeattachment/masive_load', ['uses' => 'PersonRepresentativeAttachmentController@masiveLoad']);

   //CRUD PreviewRegisterCode
   $router->post('/previewregistercode', ['uses' => 'PreviewRegisterCodeController@post']);
   $router->get('/previewregistercode', ['uses' => 'PreviewRegisterCodeController@get']);
   $router->get('/previewregistercode/paginate', ['uses' => 'PreviewRegisterCodeController@paginate']);
   $router->get('/previewregistercode/backup', ['uses' => 'PreviewRegisterCodeController@backup']);
   $router->put('/previewregistercode', ['uses' => 'PreviewRegisterCodeController@put']);
   $router->delete('/previewregistercode', ['uses' => 'PreviewRegisterCodeController@delete']);
   $router->post('/previewregistercode/masive_load', ['uses' => 'PreviewRegisterCodeController@masiveLoad']);

   //CRUD SystemName
   $router->post('/systemname', ['uses' => 'SystemNameController@post']);
   $router->get('/systemname', ['uses' => 'SystemNameController@get']);
   $router->get('/systemname/paginate', ['uses' => 'SystemNameController@paginate']);
   $router->get('/systemname/backup', ['uses' => 'SystemNameController@backup']);
   $router->put('/systemname', ['uses' => 'SystemNameController@put']);
   $router->delete('/systemname', ['uses' => 'SystemNameController@delete']);
   $router->post('/systemname/masive_load', ['uses' => 'SystemNameController@masiveLoad']);

   //CRUD Language
   $router->post('/language', ['uses' => 'LanguageController@post']);
   $router->post('/language/save_languajes', ['uses' => 'LanguageController@save_languajes']);
   $router->get('/language', ['uses' => 'LanguageController@get']);
   $router->get('/language/paginate', ['uses' => 'LanguageController@paginate']);
   $router->get('/language/backup', ['uses' => 'LanguageController@backup']);
   $router->put('/language', ['uses' => 'LanguageController@put']);
   $router->delete('/language', ['uses' => 'LanguageController@delete']);
   $router->post('/language/masive_load', ['uses' => 'LanguageController@masiveLoad']);

   //CRUD EstablishmentPicture
   $router->post('/establishmentpicture', ['uses' => 'EstablishmentPictureController@post']);
   $router->get('/establishmentpicture', ['uses' => 'EstablishmentPictureController@get']);
   $router->get('/establishmentpicture/paginate', ['uses' => 'EstablishmentPictureController@paginate']);
   $router->get('/establishmentpicture/backup', ['uses' => 'EstablishmentPictureController@backup']);
   $router->get('/establishmentpicture/get_by_establishment_id', ['uses' => 'EstablishmentPictureController@getByEstablishmentId']);
   $router->put('/establishmentpicture', ['uses' => 'EstablishmentPictureController@put']);
   $router->delete('/establishmentpicture', ['uses' => 'EstablishmentPictureController@delete']);
   $router->post('/establishmentpicture/masive_load', ['uses' => 'EstablishmentPictureController@masiveLoad']);

   //CRUD Ubication
   $router->post('/ubication', ['uses' => 'UbicationController@post']);
   $router->get('/ubication', ['uses' => 'UbicationController@get']);
   $router->get('/ubication/paginate', ['uses' => 'UbicationController@paginate']);
   $router->get('/ubication/filtered', ['uses' => 'UbicationController@filtered']);
   $router->get('/ubication/filtered-paginate', ['uses' => 'UbicationController@filtered_paginate']);
   $router->get('/ubication/get_by_id_lower', ['uses' => 'UbicationController@get_by_id_lower']);
   $router->get('/ubication/backup', ['uses' => 'UbicationController@backup']);
   $router->put('/ubication', ['uses' => 'UbicationController@put']);
   $router->delete('/ubication', ['uses' => 'UbicationController@delete']);
   $router->post('/ubication/masive_load', ['uses' => 'UbicationController@masiveLoad']);

   //CRUD Worker
   $router->post('/worker', ['uses' => 'WorkerController@post']);
   $router->get('/worker', ['uses' => 'WorkerController@get']);
   $router->get('/worker/paginate', ['uses' => 'WorkerController@paginate']);
   $router->get('/worker/backup', ['uses' => 'WorkerController@backup']);
   $router->put('/worker', ['uses' => 'WorkerController@put']);
   $router->delete('/worker', ['uses' => 'WorkerController@delete']);
   $router->post('/worker/masive_load', ['uses' => 'WorkerController@masiveLoad']);

   //CRUD Gender
   $router->post('/gender', ['uses' => 'GenderController@post']);
   $router->get('/gender', ['uses' => 'GenderController@get']);
   $router->get('/gender/paginate', ['uses' => 'GenderController@paginate']);
   $router->get('/gender/backup', ['uses' => 'GenderController@backup']);
   $router->put('/gender', ['uses' => 'GenderController@put']);
   $router->delete('/gender', ['uses' => 'GenderController@delete']);
   $router->post('/gender/masive_load', ['uses' => 'GenderController@masiveLoad']);

   //CRUD WorkerGroup
   $router->post('/workergroup', ['uses' => 'WorkerGroupController@post']);
   $router->get('/workergroup', ['uses' => 'WorkerGroupController@get']);
   $router->get('/workergroup/paginate', ['uses' => 'WorkerGroupController@paginate']);
   $router->get('/workergroup/backup', ['uses' => 'WorkerGroupController@backup']);
   $router->put('/workergroup', ['uses' => 'WorkerGroupController@put']);
   $router->delete('/workergroup', ['uses' => 'WorkerGroupController@delete']);
   $router->post('/workergroup/masive_load', ['uses' => 'WorkerGroupController@masiveLoad']);

   //CRUD EstablishmentPropertyType
   $router->post('/establishmentpropertytype', ['uses' => 'EstablishmentPropertyTypeController@post']);
   $router->get('/establishmentpropertytype', ['uses' => 'EstablishmentPropertyTypeController@get']);
   $router->get('/establishmentpropertytype/paginate', ['uses' => 'EstablishmentPropertyTypeController@paginate']);
   $router->get('/establishmentpropertytype/backup', ['uses' => 'EstablishmentPropertyTypeController@backup']);
   $router->put('/establishmentpropertytype', ['uses' => 'EstablishmentPropertyTypeController@put']);
   $router->delete('/establishmentpropertytype', ['uses' => 'EstablishmentPropertyTypeController@delete']);
   $router->post('/establishmentpropertytype/masive_load', ['uses' => 'EstablishmentPropertyTypeController@masiveLoad']);

   //CRUD GroupGiven
   $router->post('/groupgiven', ['uses' => 'GroupGivenController@post']);
   $router->get('/groupgiven', ['uses' => 'GroupGivenController@get']);
   $router->get('/groupgiven/paginate', ['uses' => 'GroupGivenController@paginate']);
   $router->get('/groupgiven/backup', ['uses' => 'GroupGivenController@backup']);
   $router->put('/groupgiven', ['uses' => 'GroupGivenController@put']);
   $router->delete('/groupgiven', ['uses' => 'GroupGivenController@delete']);
   $router->post('/groupgiven/masive_load', ['uses' => 'GroupGivenController@masiveLoad']);

   //CRUD State
   $router->post('/state', ['uses' => 'StateController@post']);
   $router->get('/state', ['uses' => 'StateController@get']);
   $router->get('/state/paginate', ['uses' => 'StateController@paginate']);
   $router->get('/state/backup', ['uses' => 'StateController@backup']);
   $router->put('/state', ['uses' => 'StateController@put']);
   $router->delete('/state', ['uses' => 'StateController@delete']);
   $router->post('/state/masive_load', ['uses' => 'StateController@masiveLoad']);

   //CRUD EstablishmentState
   $router->post('/establishmentstate', ['uses' => 'EstablishmentStateController@post']);
   $router->get('/establishmentstate', ['uses' => 'EstablishmentStateController@get']);
   $router->get('/establishmentstate/paginate', ['uses' => 'EstablishmentStateController@paginate']);
   $router->get('/establishmentstate/backup', ['uses' => 'EstablishmentStateController@backup']);
   $router->put('/establishmentstate', ['uses' => 'EstablishmentStateController@put']);
   $router->delete('/establishmentstate', ['uses' => 'EstablishmentStateController@delete']);
   $router->post('/establishmentstate/masive_load', ['uses' => 'EstablishmentStateController@masiveLoad']);

   //CRUD EstablishmentCertification
   $router->post('/establishmentcertification', ['uses' => 'EstablishmentCertificationController@post']);
   $router->get('/establishmentcertification', ['uses' => 'EstablishmentCertificationController@get']);
   $router->get('/establishmentcertification/paginate', ['uses' => 'EstablishmentCertificationController@paginate']);
   $router->get('/establishmentcertification/backup', ['uses' => 'EstablishmentCertificationController@backup']);
   $router->put('/establishmentcertification', ['uses' => 'EstablishmentCertificationController@put']);
   $router->delete('/establishmentcertification', ['uses' => 'EstablishmentCertificationController@delete']);
   $router->post('/establishmentcertification/masive_load', ['uses' => 'EstablishmentCertificationController@masiveLoad']);

   //CRUD EstablishmentCertificationType
   $router->post('/establishmentcertificationtype', ['uses' => 'EstablishmentCertificationTypeController@post']);
   $router->get('/establishmentcertificationtype', ['uses' => 'EstablishmentCertificationTypeController@get']);
   $router->get('/establishmentcertificationtype/paginate', ['uses' => 'EstablishmentCertificationTypeController@paginate']);
   $router->get('/establishmentcertificationtype/filtered', ['uses' => 'EstablishmentCertificationTypeController@filtered']);
   $router->get('/establishmentcertificationtype/filtered-paginate', ['uses' => 'EstablishmentCertificationTypeController@filtered_paginate']);
   $router->get('/establishmentcertificationtype/backup', ['uses' => 'EstablishmentCertificationTypeController@backup']);
   $router->put('/establishmentcertificationtype', ['uses' => 'EstablishmentCertificationTypeController@put']);
   $router->delete('/establishmentcertificationtype', ['uses' => 'EstablishmentCertificationTypeController@delete']);
   $router->post('/establishmentcertificationtype/masive_load', ['uses' => 'EstablishmentCertificationTypeController@masiveLoad']);

   //CRUD EstablishmentCertificationAttachment
   $router->post('/establishmentcertificationattachment', ['uses' => 'EstablishmentCertificationAttachmentController@post']);
   $router->get('/establishmentcertificationattachment', ['uses' => 'EstablishmentCertificationAttachmentController@get']);
   $router->get('/establishmentcertificationattachment/paginate', ['uses' => 'EstablishmentCertificationAttachmentController@paginate']);
   $router->get('/establishmentcertificationattachment/backup', ['uses' => 'EstablishmentCertificationAttachmentController@backup']);
   $router->put('/establishmentcertificationattachment', ['uses' => 'EstablishmentCertificationAttachmentController@put']);
   $router->delete('/establishmentcertificationattachment', ['uses' => 'EstablishmentCertificationAttachmentController@delete']);
   $router->post('/establishmentcertificationattachment/masive_load', ['uses' => 'EstablishmentCertificationAttachmentController@masiveLoad']);

   //CRUD GroupType
   $router->post('/grouptype', ['uses' => 'GroupTypeController@post']);
   $router->get('/grouptype', ['uses' => 'GroupTypeController@get']);
   $router->get('/grouptype/paginate', ['uses' => 'GroupTypeController@paginate']);
   $router->get('/grouptype/backup', ['uses' => 'GroupTypeController@backup']);
   $router->put('/grouptype', ['uses' => 'GroupTypeController@put']);
   $router->delete('/grouptype', ['uses' => 'GroupTypeController@delete']);
   $router->post('/grouptype/masive_load', ['uses' => 'GroupTypeController@masiveLoad']);

   //CRUD RucNameType
   $router->post('/rucnametype', ['uses' => 'RucNameTypeController@post']);
   $router->get('/rucnametype', ['uses' => 'RucNameTypeController@get']);
   $router->get('/rucnametype/paginate', ['uses' => 'RucNameTypeController@paginate']);
   $router->get('/rucnametype/backup', ['uses' => 'RucNameTypeController@backup']);
   $router->put('/rucnametype', ['uses' => 'RucNameTypeController@put']);
   $router->delete('/rucnametype', ['uses' => 'RucNameTypeController@delete']);
   $router->post('/rucnametype/masive_load', ['uses' => 'RucNameTypeController@masiveLoad']);

   //CRUD Agreement
   $router->post('/agreement', ['uses' => 'AgreementController@post']);
   $router->get('/agreement', ['uses' => 'AgreementController@get']);
   $router->get('/agreement/paginate', ['uses' => 'AgreementController@paginate']);
   $router->get('/agreement/backup', ['uses' => 'AgreementController@backup']);
   $router->put('/agreement', ['uses' => 'AgreementController@put']);
   $router->delete('/agreement', ['uses' => 'AgreementController@delete']);
   $router->post('/agreement/masive_load', ['uses' => 'AgreementController@masiveLoad']);

   //CRUD FloorAuthorizationCertificate
   $router->post('/floorauthorizationcertificate', ['uses' => 'FloorAuthorizationCertificateController@post']);
   $router->get('/floorauthorizationcertificate', ['uses' => 'FloorAuthorizationCertificateController@get']);
   $router->get('/floorauthorizationcertificate/get_by_register_id', ['uses' => 'FloorAuthorizationCertificateController@get_by_register_id']);
   $router->get('/floorauthorizationcertificate/paginate', ['uses' => 'FloorAuthorizationCertificateController@paginate']);
   $router->get('/floorauthorizationcertificate/backup', ['uses' => 'FloorAuthorizationCertificateController@backup']);
   $router->put('/floorauthorizationcertificate', ['uses' => 'FloorAuthorizationCertificateController@put']);
   $router->delete('/floorauthorizationcertificate', ['uses' => 'FloorAuthorizationCertificateController@delete']);
   $router->post('/floorauthorizationcertificate/masive_load', ['uses' => 'FloorAuthorizationCertificateController@masiveLoad']);
});
