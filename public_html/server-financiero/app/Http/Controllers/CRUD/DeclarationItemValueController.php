<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\DeclarationItemValue;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeclarationItemValueController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(DeclarationItemValue::get(),200);
       } else {
          $declarationitemvalue = DeclarationItemValue::findOrFail($id);
          $attach = [];
          return response()->json(["DeclarationItemValue"=>$declarationitemvalue, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(DeclarationItemValue::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $declarationitemvalue = new DeclarationItemValue();
          $lastDeclarationItemValue = DeclarationItemValue::orderBy('id')->get()->last();
          if($lastDeclarationItemValue) {
             $declarationitemvalue->id = $lastDeclarationItemValue->id + 1;
          } else {
             $declarationitemvalue->id = 1;
          }
          $declarationitemvalue->value = $result['value'];
          $declarationitemvalue->declaration_item_id = $result['declaration_item_id'];
          $declarationitemvalue->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($declarationitemvalue,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $declarationitemvalue = DeclarationItemValue::where('id',$result['id'])->update([
             'value'=>$result['value'],
             'declaration_item_id'=>$result['declaration_item_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($declarationitemvalue,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return DeclarationItemValue::destroy($id);
    }

    function backup(Request $data)
    {
       $declarationitemvalues = DeclarationItemValue::get();
       $toReturn = [];
       foreach( $declarationitemvalues as $declarationitemvalue) {
          $attach = [];
          array_push($toReturn, ["DeclarationItemValue"=>$declarationitemvalue, "attach"=>$attach]);
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
         $result = $row['DeclarationItemValue'];
         $exist = DeclarationItemValue::where('id',$result['id'])->first();
         if ($exist) {
           DeclarationItemValue::where('id', $result['id'])->update([
             'value'=>$result['value'],
             'declaration_item_id'=>$result['declaration_item_id'],
           ]);
         } else {
          $declarationitemvalue = new DeclarationItemValue();
          $declarationitemvalue->id = $result['id'];
          $declarationitemvalue->value = $result['value'];
          $declarationitemvalue->declaration_item_id = $result['declaration_item_id'];
          $declarationitemvalue->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}