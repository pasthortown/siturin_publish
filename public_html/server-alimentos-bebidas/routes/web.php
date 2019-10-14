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
});

$router->group(['middleware' => ['auth']], function () use ($router) {
   $router->post('/user/password_change', ['uses' => 'AuthController@passwordChange']);


   //ALIMENTOSBEBIDAS

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

   //CRUD Register
   $router->post('/register', ['uses' => 'RegisterController@post']);
   $router->get('/register', ['uses' => 'RegisterController@get']);
   $router->get('/register/paginate', ['uses' => 'RegisterController@paginate']);
   $router->get('/register/backup', ['uses' => 'RegisterController@backup']);
   $router->put('/register', ['uses' => 'RegisterController@put']);
   $router->delete('/register', ['uses' => 'RegisterController@delete']);
   $router->post('/register/masive_load', ['uses' => 'RegisterController@masiveLoad']);

   //CRUD Capacity
   $router->post('/capacity', ['uses' => 'CapacityController@post']);
   $router->get('/capacity', ['uses' => 'CapacityController@get']);
   $router->get('/capacity/paginate', ['uses' => 'CapacityController@paginate']);
   $router->get('/capacity/backup', ['uses' => 'CapacityController@backup']);
   $router->put('/capacity', ['uses' => 'CapacityController@put']);
   $router->delete('/capacity', ['uses' => 'CapacityController@delete']);
   $router->post('/capacity/masive_load', ['uses' => 'CapacityController@masiveLoad']);

   //CRUD RegisterType
   $router->post('/registertype', ['uses' => 'RegisterTypeController@post']);
   $router->get('/registertype', ['uses' => 'RegisterTypeController@get']);
   $router->get('/registertype/paginate', ['uses' => 'RegisterTypeController@paginate']);
   $router->get('/registertype/backup', ['uses' => 'RegisterTypeController@backup']);
   $router->put('/registertype', ['uses' => 'RegisterTypeController@put']);
   $router->delete('/registertype', ['uses' => 'RegisterTypeController@delete']);
   $router->post('/registertype/masive_load', ['uses' => 'RegisterTypeController@masiveLoad']);

   //CRUD Requisite
   $router->post('/requisite', ['uses' => 'RequisiteController@post']);
   $router->get('/requisite', ['uses' => 'RequisiteController@get']);
   $router->get('/requisite/paginate', ['uses' => 'RequisiteController@paginate']);
   $router->get('/requisite/backup', ['uses' => 'RequisiteController@backup']);
   $router->put('/requisite', ['uses' => 'RequisiteController@put']);
   $router->delete('/requisite', ['uses' => 'RequisiteController@delete']);
   $router->post('/requisite/masive_load', ['uses' => 'RequisiteController@masiveLoad']);

   //CRUD RegisterRequisite
   $router->post('/registerrequisite', ['uses' => 'RegisterRequisiteController@post']);
   $router->get('/registerrequisite', ['uses' => 'RegisterRequisiteController@get']);
   $router->get('/registerrequisite/paginate', ['uses' => 'RegisterRequisiteController@paginate']);
   $router->get('/registerrequisite/backup', ['uses' => 'RegisterRequisiteController@backup']);
   $router->put('/registerrequisite', ['uses' => 'RegisterRequisiteController@put']);
   $router->delete('/registerrequisite', ['uses' => 'RegisterRequisiteController@delete']);
   $router->post('/registerrequisite/masive_load', ['uses' => 'RegisterRequisiteController@masiveLoad']);

   //CRUD State
   $router->post('/state', ['uses' => 'StateController@post']);
   $router->get('/state', ['uses' => 'StateController@get']);
   $router->get('/state/paginate', ['uses' => 'StateController@paginate']);
   $router->get('/state/backup', ['uses' => 'StateController@backup']);
   $router->put('/state', ['uses' => 'StateController@put']);
   $router->delete('/state', ['uses' => 'StateController@delete']);
   $router->post('/state/masive_load', ['uses' => 'StateController@masiveLoad']);

   //CRUD RegisterState
   $router->post('/registerstate', ['uses' => 'RegisterStateController@post']);
   $router->get('/registerstate', ['uses' => 'RegisterStateController@get']);
   $router->get('/registerstate/paginate', ['uses' => 'RegisterStateController@paginate']);
   $router->get('/registerstate/backup', ['uses' => 'RegisterStateController@backup']);
   $router->put('/registerstate', ['uses' => 'RegisterStateController@put']);
   $router->delete('/registerstate', ['uses' => 'RegisterStateController@delete']);
   $router->post('/registerstate/masive_load', ['uses' => 'RegisterStateController@masiveLoad']);

   //CRUD TariffType
   $router->post('/tarifftype', ['uses' => 'TariffTypeController@post']);
   $router->get('/tarifftype', ['uses' => 'TariffTypeController@get']);
   $router->get('/tarifftype/paginate', ['uses' => 'TariffTypeController@paginate']);
   $router->get('/tarifftype/backup', ['uses' => 'TariffTypeController@backup']);
   $router->put('/tarifftype', ['uses' => 'TariffTypeController@put']);
   $router->delete('/tarifftype', ['uses' => 'TariffTypeController@delete']);
   $router->post('/tarifftype/masive_load', ['uses' => 'TariffTypeController@masiveLoad']);

   //CRUD Tariff
   $router->post('/tariff', ['uses' => 'TariffController@post']);
   $router->get('/tariff', ['uses' => 'TariffController@get']);
   $router->get('/tariff/paginate', ['uses' => 'TariffController@paginate']);
   $router->get('/tariff/backup', ['uses' => 'TariffController@backup']);
   $router->put('/tariff', ['uses' => 'TariffController@put']);
   $router->delete('/tariff', ['uses' => 'TariffController@delete']);
   $router->post('/tariff/masive_load', ['uses' => 'TariffController@masiveLoad']);

   //CRUD Group
   $router->post('/group', ['uses' => 'GroupController@post']);
   $router->get('/group', ['uses' => 'GroupController@get']);
   $router->get('/group/paginate', ['uses' => 'GroupController@paginate']);
   $router->get('/group/backup', ['uses' => 'GroupController@backup']);
   $router->put('/group', ['uses' => 'GroupController@put']);
   $router->delete('/group', ['uses' => 'GroupController@delete']);
   $router->post('/group/masive_load', ['uses' => 'GroupController@masiveLoad']);

   //CRUD GroupType
   $router->post('/grouptype', ['uses' => 'GroupTypeController@post']);
   $router->get('/grouptype', ['uses' => 'GroupTypeController@get']);
   $router->get('/grouptype/paginate', ['uses' => 'GroupTypeController@paginate']);
   $router->get('/grouptype/backup', ['uses' => 'GroupTypeController@backup']);
   $router->put('/grouptype', ['uses' => 'GroupTypeController@put']);
   $router->delete('/grouptype', ['uses' => 'GroupTypeController@delete']);
   $router->post('/grouptype/masive_load', ['uses' => 'GroupTypeController@masiveLoad']);

   //CRUD CapacityType
   $router->post('/capacitytype', ['uses' => 'CapacityTypeController@post']);
   $router->get('/capacitytype', ['uses' => 'CapacityTypeController@get']);
   $router->get('/capacitytype/paginate', ['uses' => 'CapacityTypeController@paginate']);
   $router->get('/capacitytype/backup', ['uses' => 'CapacityTypeController@backup']);
   $router->put('/capacitytype', ['uses' => 'CapacityTypeController@put']);
   $router->delete('/capacitytype', ['uses' => 'CapacityTypeController@delete']);
   $router->post('/capacitytype/masive_load', ['uses' => 'CapacityTypeController@masiveLoad']);

   //CRUD CapacityPicture
   $router->post('/capacitypicture', ['uses' => 'CapacityPictureController@post']);
   $router->get('/capacitypicture', ['uses' => 'CapacityPictureController@get']);
   $router->get('/capacitypicture/paginate', ['uses' => 'CapacityPictureController@paginate']);
   $router->get('/capacitypicture/backup', ['uses' => 'CapacityPictureController@backup']);
   $router->put('/capacitypicture', ['uses' => 'CapacityPictureController@put']);
   $router->delete('/capacitypicture', ['uses' => 'CapacityPictureController@delete']);
   $router->post('/capacitypicture/masive_load', ['uses' => 'CapacityPictureController@masiveLoad']);

   //CRUD Approval
   $router->post('/approval', ['uses' => 'ApprovalController@post']);
   $router->get('/approval', ['uses' => 'ApprovalController@get']);
   $router->get('/approval/paginate', ['uses' => 'ApprovalController@paginate']);
   $router->get('/approval/backup', ['uses' => 'ApprovalController@backup']);
   $router->put('/approval', ['uses' => 'ApprovalController@put']);
   $router->delete('/approval', ['uses' => 'ApprovalController@delete']);
   $router->post('/approval/masive_load', ['uses' => 'ApprovalController@masiveLoad']);

   //CRUD ApprovalState
   $router->post('/approvalstate', ['uses' => 'ApprovalStateController@post']);
   $router->get('/approvalstate', ['uses' => 'ApprovalStateController@get']);
   $router->get('/approvalstate/paginate', ['uses' => 'ApprovalStateController@paginate']);
   $router->get('/approvalstate/backup', ['uses' => 'ApprovalStateController@backup']);
   $router->put('/approvalstate', ['uses' => 'ApprovalStateController@put']);
   $router->delete('/approvalstate', ['uses' => 'ApprovalStateController@delete']);
   $router->post('/approvalstate/masive_load', ['uses' => 'ApprovalStateController@masiveLoad']);

   //CRUD ApprovalStateAttachment
   $router->post('/approvalstateattachment', ['uses' => 'ApprovalStateAttachmentController@post']);
   $router->get('/approvalstateattachment', ['uses' => 'ApprovalStateAttachmentController@get']);
   $router->get('/approvalstateattachment/paginate', ['uses' => 'ApprovalStateAttachmentController@paginate']);
   $router->get('/approvalstateattachment/backup', ['uses' => 'ApprovalStateAttachmentController@backup']);
   $router->put('/approvalstateattachment', ['uses' => 'ApprovalStateAttachmentController@put']);
   $router->delete('/approvalstateattachment', ['uses' => 'ApprovalStateAttachmentController@delete']);
   $router->post('/approvalstateattachment/masive_load', ['uses' => 'ApprovalStateAttachmentController@masiveLoad']);

   //CRUD ApprovalStateReport
   $router->post('/approvalstatereport', ['uses' => 'ApprovalStateReportController@post']);
   $router->get('/approvalstatereport', ['uses' => 'ApprovalStateReportController@get']);
   $router->get('/approvalstatereport/paginate', ['uses' => 'ApprovalStateReportController@paginate']);
   $router->get('/approvalstatereport/backup', ['uses' => 'ApprovalStateReportController@backup']);
   $router->put('/approvalstatereport', ['uses' => 'ApprovalStateReportController@put']);
   $router->delete('/approvalstatereport', ['uses' => 'ApprovalStateReportController@delete']);
   $router->post('/approvalstatereport/masive_load', ['uses' => 'ApprovalStateReportController@masiveLoad']);

   //CRUD Procedure
   $router->post('/procedure', ['uses' => 'ProcedureController@post']);
   $router->get('/procedure', ['uses' => 'ProcedureController@get']);
   $router->get('/procedure/paginate', ['uses' => 'ProcedureController@paginate']);
   $router->get('/procedure/backup', ['uses' => 'ProcedureController@backup']);
   $router->put('/procedure', ['uses' => 'ProcedureController@put']);
   $router->delete('/procedure', ['uses' => 'ProcedureController@delete']);
   $router->post('/procedure/masive_load', ['uses' => 'ProcedureController@masiveLoad']);

   //CRUD ProcedureJustification
   $router->post('/procedurejustification', ['uses' => 'ProcedureJustificationController@post']);
   $router->get('/procedurejustification', ['uses' => 'ProcedureJustificationController@get']);
   $router->get('/procedurejustification/paginate', ['uses' => 'ProcedureJustificationController@paginate']);
   $router->get('/procedurejustification/backup', ['uses' => 'ProcedureJustificationController@backup']);
   $router->put('/procedurejustification', ['uses' => 'ProcedureJustificationController@put']);
   $router->delete('/procedurejustification', ['uses' => 'ProcedureJustificationController@delete']);
   $router->post('/procedurejustification/masive_load', ['uses' => 'ProcedureJustificationController@masiveLoad']);

   //CRUD RegisterProcedure
   $router->post('/registerprocedure', ['uses' => 'RegisterProcedureController@post']);
   $router->get('/registerprocedure', ['uses' => 'RegisterProcedureController@get']);
   $router->get('/registerprocedure/paginate', ['uses' => 'RegisterProcedureController@paginate']);
   $router->get('/registerprocedure/backup', ['uses' => 'RegisterProcedureController@backup']);
   $router->put('/registerprocedure', ['uses' => 'RegisterProcedureController@put']);
   $router->delete('/registerprocedure', ['uses' => 'RegisterProcedureController@delete']);
   $router->post('/registerprocedure/masive_load', ['uses' => 'RegisterProcedureController@masiveLoad']);

   //CRUD PropertyTitleAttachment
   $router->post('/propertytitleattachment', ['uses' => 'PropertyTitleAttachmentController@post']);
   $router->get('/propertytitleattachment', ['uses' => 'PropertyTitleAttachmentController@get']);
   $router->get('/propertytitleattachment/paginate', ['uses' => 'PropertyTitleAttachmentController@paginate']);
   $router->get('/propertytitleattachment/backup', ['uses' => 'PropertyTitleAttachmentController@backup']);
   $router->put('/propertytitleattachment', ['uses' => 'PropertyTitleAttachmentController@put']);
   $router->delete('/propertytitleattachment', ['uses' => 'PropertyTitleAttachmentController@delete']);
   $router->post('/propertytitleattachment/masive_load', ['uses' => 'PropertyTitleAttachmentController@masiveLoad']);

   //CRUD AuthorizationAttachment
   $router->post('/authorizationattachment', ['uses' => 'AuthorizationAttachmentController@post']);
   $router->get('/authorizationattachment', ['uses' => 'AuthorizationAttachmentController@get']);
   $router->get('/authorizationattachment/paginate', ['uses' => 'AuthorizationAttachmentController@paginate']);
   $router->get('/authorizationattachment/backup', ['uses' => 'AuthorizationAttachmentController@backup']);
   $router->put('/authorizationattachment', ['uses' => 'AuthorizationAttachmentController@put']);
   $router->delete('/authorizationattachment', ['uses' => 'AuthorizationAttachmentController@delete']);
   $router->post('/authorizationattachment/masive_load', ['uses' => 'AuthorizationAttachmentController@masiveLoad']);
});
