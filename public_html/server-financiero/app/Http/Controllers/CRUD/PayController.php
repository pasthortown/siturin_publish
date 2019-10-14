<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Pay;
use App\PayAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PayController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Pay::get(),200);
       } else {
          $pay = Pay::findOrFail($id);
          $attach = [];
          return response()->json(["Pay"=>$pay, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Pay::paginate($size),200);
    }

    function get_report(Request $data)
    {
      $token = $data->header('api_token'); 
      $result = $data->json()->all();
      $desde = $result['desde'];
      $hasta = $result['hasta'];
      $pays = Pay::where('created_at','>=',$desde)->where('created_at','<=',$hasta)->orderby('created_at','DESC')->get();
      $toReturn = [];
      foreach ($pays as $pay) {
         $ruc_id = $pay->ruc_id;
         $ruc = json_decode($this->httpGet(env('API_BASE').'ruc/?id='.$ruc_id, null, null, $token));
         $user = json_decode($this->httpGet(env('API_AUTH').'user/?id='.$ruc->Ruc->contact_user_id, null, null, $token));
         array_push($toReturn, ["RUC"=>$ruc, "pay"=>$pay, "user"=>$user]);
      }
      return response()->json($toReturn,200);
    }
    
    function process_pays(Request $data) {
      $result = $data->json()->all();
      $pays = $result['pays'];
      $toReturn = [];
      foreach($pays as $pay){
         $referencia_transaccion = $pay["referencia_transaccion"];
         $code = $pay["codigo_del_tercero"];
         $valor = $pay["valor"];
         $payOnBDD = Pay::where('code', $code)->first();
         $payed = false;
         if ($valor >= $payOnBDD->amount_to_pay) {
            $payed = true;
         }
         $amount_to_pay = $payOnBDD->amount_to_pay - $valor;
         if(!($payOnBDD->payed)) {
            DB::beginTransaction();
            $payOnBDD = Pay::where('code', $code)->update([
               'amount_to_pay'=> round($amount_to_pay,2),
               'amount_payed'=>round($valor,2),
               'pay_date'=>date("Y-m-d H:i:s"),
               'payed'=>$payed,
            ]);
            array_push($toReturn, ["referencia_transaccion"=>$pay["referencia_transaccion"], "codigo_del_tercero"=>$pay["codigo_del_tercero"], "valor"=>$pay["valor"], "payed"=>$payed]);
            DB::commit(); 
         }
      }
      return response()->json($toReturn,200);
    }

    function get_by_ruc_id(Request $data)
    {
       $id = $data['id'];
       $pays = Pay::where('ruc_id', $id)->orderBy('created_at', 'DESC')->get();
       $toReturn = [];
       foreach($pays as $pay) {
         $payattachment = PayAttachment::where('pay_id', $pay->id)->first();
         if ($payattachment) {
            $newPay = [
               "id" => $pay->id,
               "amount_payed" => $pay->amount_payed,
               "amount_to_pay" => $pay->amount_to_pay,
               "pay_date" => $pay->pay_date,
               "payed" => $pay->payed,
               "code" => $pay->code,
               "max_pay_date" => $pay->max_pay_date,
               "ruc_id" => $pay->ruc_id,
               "amount_to_pay_taxes" => $pay->amount_to_pay_taxes,
               "amount_to_pay_base" => $pay->amount_to_pay_base,
               "amount_to_pay_fines" => $pay->amount_to_pay_fines,
               "notes" => $pay->notes,
               "pay_attachment" => [
                  "id" => $payattachment->id,
                  "pay_attachment_file_type" => $payattachment->pay_attachment_file_type,
                  "pay_attachment_file" => $payattachment->pay_attachment_file,
                  "pay_attachment_file_name" => $payattachment->pay_attachment_file_name,
                  "pay_id" => $payattachment->pay_id,
               ]
            ];
            array_push($toReturn, $newPay);
         } else {
            $newPay = [
               "id" => $pay->id,
               "amount_payed" => $pay->amount_payed,
               "amount_to_pay" => $pay->amount_to_pay,
               "pay_date" => $pay->pay_date,
               "payed" => $pay->payed,
               "code" => $pay->code,
               "max_pay_date" => $pay->max_pay_date,
               "ruc_id" => $pay->ruc_id,
               "amount_to_pay_taxes" => $pay->amount_to_pay_taxes,
               "amount_to_pay_base" => $pay->amount_to_pay_base,
               "amount_to_pay_fines" => $pay->amount_to_pay_fines,
               "notes" => $pay->notes,
            ];
            array_push($toReturn, $newPay);
         }
       }
       return response()->json($toReturn,200);
    }

    function get_by_ruc_number(Request $data)
    {
      $token = $data->header('api_token');
      $number = $data['number'];
      $ruc = json_decode($this->httpGet(env('API_BASE').'ruc/get_by_ruc_number?number='.$number, null, null, $token));
      try {
        return response()->json(Pay::where('ruc_id', $ruc->id)->orderBy('created_at', 'DESC')->get(),200);
      } catch (Exception $e) {
          return response()->json([],200);
      }  
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $pay = new Pay();
          $lastPay = Pay::orderBy('id')->get()->last();
          if($lastPay) {
             $pay->id = $lastPay->id + 1;
          } else {
             $pay->id = 1;
          }
          $pay->amount_payed = round($result['amount_payed'],2);
          $pay->amount_to_pay = round($result['amount_to_pay'],2);
          $pay->pay_date = $result['pay_date'];
          $pay->payed = $result['payed'];
          $pay->code = $result['code'];
          $pay->max_pay_date = $result['max_pay_date'];
          $pay->ruc_id = $result['ruc_id'];
          $pay->amount_to_pay_taxes = round($result['amount_to_pay_taxes'],2);
          $pay->amount_to_pay_base = round($result['amount_to_pay_base'],2);
          $pay->amount_to_pay_fines = round($result['amount_to_pay_fines'],2);
          $pay->notes = $result['notes'];
          $pay->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($pay,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $pay = Pay::where('id',$result['id'])->update([
             'amount_payed'=>round($result['amount_payed'],2),
             'amount_to_pay'=>round($result['amount_to_pay'],2),
             'pay_date'=>$result['pay_date'],
             'payed'=>$result['payed'],
             'code'=>$result['code'],
             'max_pay_date'=>$result['max_pay_date'],
             'ruc_id'=>$result['ruc_id'],
             'amount_to_pay_taxes'=>round($result['amount_to_pay_taxes'],2),
             'amount_to_pay_base'=>round($result['amount_to_pay_base'],2),
             'amount_to_pay_fines'=>round($result['amount_to_pay_fines'],2),
             'notes'=>$result['notes'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($pay,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Pay::destroy($id);
    }

    function backup(Request $data)
    {
       $pays = Pay::get();
       $toReturn = [];
       foreach( $pays as $pay) {
          $attach = [];
          array_push($toReturn, ["Pay"=>$pay, "attach"=>$attach]);
       }
       return response()->json($toReturn,200);
    }

    function masiveLoad(Request $data)
    {
      $incomming = $data->json()->all();
      $masiveData = $incomming['data'];
      try{
       DB::beginTransaction();
       foreach($masiveData as $row) {
         $result = $row['Pay'];
         $exist = Pay::where('id',$result['id'])->first();
         if ($exist) {
           Pay::where('id', $result['id'])->update([
             'amount_payed'=>round($result['amount_payed'],2),
             'amount_to_pay'=>round($result['amount_to_pay'],2),
             'pay_date'=>$result['pay_date'],
             'payed'=>$result['payed'],
             'code'=>$result['code'],
             'max_pay_date'=>$result['max_pay_date'],
             'ruc_id'=>$result['ruc_id'],
             'amount_to_pay_taxes'=>round($result['amount_to_pay_taxes'],2),
             'amount_to_pay_base'=>round($result['amount_to_pay_base'],2),
             'amount_to_pay_fines'=>round($result['amount_to_pay_fines'],2),
             'notes'=>$result['notes'],
           ]);
         } else {
          $pay = new Pay();
          $pay->id = $result['id'];
          $pay->amount_payed = round($result['amount_payed'],2);
          $pay->amount_to_pay = round($result['amount_to_pay'],2);
          $pay->pay_date = $result['pay_date'];
          $pay->payed = $result['payed'];
          $pay->code = $result['code'];
          $pay->max_pay_date = $result['max_pay_date'];
          $pay->ruc_id = $result['ruc_id'];
          $pay->amount_to_pay_taxes = round($result['amount_to_pay_taxes'],2);
          $pay->amount_to_pay_base = round($result['amount_to_pay_base'],2);
          $pay->amount_to_pay_fines = round($result['amount_to_pay_fines'],2);
          $pay->notes = $result['notes'];
          $pay->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
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
