<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\PayTax;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PayTaxController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
         return response()->json(PayTax::orderBy('year','ASC')->orderBy('trimester','ASC')->get(),200);
       } else {
          $paytax = PayTax::findOrFail($id);
          $attach = [];
          return response()->json(["PayTax"=>$paytax, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(PayTax::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $paytax = new PayTax();
          $lastPayTax = PayTax::orderBy('id')->get()->last();
          if($lastPayTax) {
             $paytax->id = $lastPayTax->id + 1;
          } else {
             $paytax->id = 1;
          }
          $paytax->year = $result['year'];
          $paytax->trimester = $result['trimester'];
          $paytax->value = $result['value'];
          $paytax->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($paytax,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $paytax = PayTax::where('id',$result['id'])->update([
             'year'=>$result['year'],
             'trimester'=>$result['trimester'],
             'value'=>$result['value'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($paytax,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return PayTax::destroy($id);
    }

    function backup(Request $data)
    {
       $paytaxes = PayTax::get();
       $toReturn = [];
       foreach( $paytaxes as $paytax) {
          $attach = [];
          array_push($toReturn, ["PayTax"=>$paytax, "attach"=>$attach]);
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
         $result = $row['PayTax'];
         $exist = PayTax::where('id',$result['id'])->first();
         if ($exist) {
           PayTax::where('id', $result['id'])->update([
             'year'=>$result['year'],
             'trimester'=>$result['trimester'],
             'value'=>$result['value'],
           ]);
         } else {
          $paytax = new PayTax();
          $paytax->id = $result['id'];
          $paytax->year = $result['year'];
          $paytax->trimester = $result['trimester'];
          $paytax->value = $result['value'];
          $paytax->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}