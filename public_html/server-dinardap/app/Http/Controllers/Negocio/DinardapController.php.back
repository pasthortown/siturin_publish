<?php

namespace App\Http\Controllers;

use Validator;
use Exception;
use App\Identification;
use App\Ruc;
use SoapClient;
use Illuminate\Http\Request;

class DinardapController extends Controller
{
  public function Cedula(Request $request) {
    $data = $request->json()->all();
    $respuesta = $this->httpPost(env('API_DINARDAP').'cedula', json_encode(['identificacion'=>$data['identificacion']]), null, null);
    $previewData = Identification::where('number', $data['identificacion'])->first();
    if (!$previewData) {
      $identification = new Identification();
      $lastIdentification = Identification::orderBy('id')->get()->last();
      if($lastIdentification) {
          $identification->id = $lastIdentification->id + 1;
      } else {
          $identification->id = 1;
      }
      $identification->number = $data['identificacion'];
      $identification->data = $respuesta;
      $identification->date = date("Y-m-d H:i:s");
      $identification->save();
    } else {
      if ($previewData->data == $respuesta) {
        $previewData->update([
          'date'=>date("Y-m-d H:i:s"),
        ]);
      } else {
        $identification = new Identification();
        $lastIdentification = Identification::orderBy('id')->get()->last();
        if($lastIdentification) {
            $identification->id = $lastIdentification->id + 1;
        } else {
            $identification->id = 1;
        }
        $identification->number = $data['identificacion'];
        $identification->data = $respuesta;
        $identification->date = date("Y-m-d H:i:s");
        $identification->save();
      }
    }
    return response()->json(json_decode($respuesta),200);
  }

  public function RUC(Request $request) {
    $data = $request->json()->all();
    $respuesta = $this->httpPost(env('API_DINARDAP').'ruc', json_encode(['RUC'=>$data['RUC']]), null, null);
    $previewData = Ruc::where('number', $data['RUC'])->first();
    if (!$previewData) {
      $ruc = new Ruc();
      $lastRuc = Ruc::orderBy('id')->get()->last();
      if($lastRuc) {
          $ruc->id = $lastRuc->id + 1;
      } else {
          $ruc->id = 1;
      }
      $ruc->number = $data['RUC'];
      $ruc->data = $respuesta;
      $ruc->date = date("Y-m-d H:i:s");
      $ruc->save();
    } else {
      if ($previewData->data == $respuesta) {
        $previewData->update([
          'date'=>date("Y-m-d H:i:s"),
        ]);
      } else {
        $ruc = new Ruc();
        $lastRuc = Ruc::orderBy('id')->get()->last();
        if($lastRuc) {
            $ruc->id = $lastRuc->id + 1;
        } else {
            $ruc->id = 1;
        }
        $ruc->number = $data['RUC'];
        $ruc->data = $respuesta;
        $ruc->date = date("Y-m-d H:i:s");
        $ruc->save();
      }
    }
    return response()->json(json_decode($respuesta),200);
  }

  protected function httpPost($url, $data=NULL, $headers = NULL, $token) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    if(!empty($data)){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    $headersSend = array('Content-Type: application/json');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headersSend);
    $response = curl_exec($ch);

    if (curl_error($ch)) {
        trigger_error('Curl Error:' . curl_error($ch));
    }
    curl_close($ch);
    return $response;
  }

}

