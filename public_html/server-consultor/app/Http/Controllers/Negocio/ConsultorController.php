<?php

namespace App\Http\Controllers;

use Validator;
use Exception;
use Illuminate\Http\Request;

class ConsultorController extends Controller
{
  function registers(Request $data) {
    $request = $data->json()->all();
    $token = $data->header('api_token');
    $toReturn = [];
    $registers_alojamiento = json_decode($this->httpGet(env('API_ALOJAMIENTO').'register',null,null,$token));
    foreach($registers_alojamiento as $register) {
      $status = json_decode($this->httpGet(env('API_ALOJAMIENTO').'registerstate/get_by_register_id?id='.$register->id,null,null,$token));
      $establishment = json_decode($this->httpGet(env('API_BASE').'establishment?id='.$register->establishment_id,null,null,$token));
      $ruc = json_decode($this->httpGet(env('API_BASE').'ruc?id='.$establishment->Establishment->ruc_id,null,null,$token));
      array_push($toReturn, ["register"=>$register, "establishment"=>$establishment->Establishment, "ruc"=>$ruc->Ruc, "states"=>$status]);
    }
    return response()->json($toReturn,200);
  }

  function registerByCode(Request $data) {
    $token = $data->header('api_token');
    $code = $data['id'];
    $toReturn = [];
    $register = json_decode($this->httpGet(env('API_ALOJAMIENTO').'register/get_by_register_code/?code='.$code,null,null,$token));
    $status = json_decode($this->httpGet(env('API_ALOJAMIENTO').'registerstate/get_by_register_id?id='.$register->id,null,null,$token));
    $establishment = json_decode($this->httpGet(env('API_BASE').'establishment?id='.$register->establishment_id,null,null,$token));
    $ruc = json_decode($this->httpGet(env('API_BASE').'ruc?id='.$establishment->Establishment->ruc_id,null,null,$token));
    array_push($toReturn, ["register"=>$register, "establishment"=>$establishment->Establishment, "ruc"=>$ruc->Ruc, "states"=>$status]);
    return response()->json($toReturn,200);
  }

  function get_registers_assigned_inspector_id(Request $data) {
    $token = $data->header('api_token');
    $toReturn = [];
    $registers_alojamiento = json_decode($this->httpGet(env('API_ALOJAMIENTO').'register/by_inspector_id?id='.$data['id'],null,null,$token));
    foreach($registers_alojamiento as $register) {
      $status = json_decode($this->httpGet(env('API_ALOJAMIENTO').'registerstate/get_by_register_id?id='.$register->id,null,null,$token));
      $establishment = json_decode($this->httpGet(env('API_BASE').'establishment?id='.$register->establishment_id,null,null,$token));
      $ruc = json_decode($this->httpGet(env('API_BASE').'ruc?id='.$establishment->Establishment->ruc_id,null,null,$token));
      array_push($toReturn, ["register"=>$register, "establishment"=>$establishment->Establishment, "ruc"=>$ruc->Ruc, "states"=>$status]);
    }
    return response()->json($toReturn,200);
  }

  function get_registers_assigned_financial_id(Request $data) {
    $token = $data->header('api_token');
    $toReturn = [];
    $registers_alojamiento = json_decode($this->httpGet(env('API_ALOJAMIENTO').'register/by_financial_id?id='.$data['id'],null,null,$token));
    foreach($registers_alojamiento as $register) {
      $status = json_decode($this->httpGet(env('API_ALOJAMIENTO').'registerstate/get_by_register_id?id='.$register->id,null,null,$token));
      $establishment = json_decode($this->httpGet(env('API_BASE').'establishment?id='.$register->establishment_id,null,null,$token));
      $ruc = json_decode($this->httpGet(env('API_BASE').'ruc?id='.$establishment->Establishment->ruc_id,null,null,$token));
      array_push($toReturn, ["register"=>$register, "establishment"=>$establishment->Establishment, "ruc"=>$ruc->Ruc, "states"=>$status]);
    }
    return response()->json($toReturn,200);
  }

  protected function httpGet($url, $data=NULL, $headers = NULL, $token) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if(!empty($data)){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    $headersSend = array();
    array_push($headersSend, 'api_token:'.$token);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headersSend);
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        trigger_error('Curl Error:' . curl_error($ch));
    }
    curl_close($ch);
    return $response;
  }
}
