<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\RegisterProcedure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegisterProcedureController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(RegisterProcedure::get(),200);
       } else {
          $registerprocedure = RegisterProcedure::findOrFail($id);
          $attach = [];
          return response()->json(["RegisterProcedure"=>$registerprocedure, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(RegisterProcedure::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $registerprocedure = new RegisterProcedure();
          $lastRegisterProcedure = RegisterProcedure::orderBy('id')->get()->last();
          if($lastRegisterProcedure) {
             $registerprocedure->id = $lastRegisterProcedure->id + 1;
          } else {
             $registerprocedure->id = 1;
          }
          $registerprocedure->date = $result['date'];
          $registerprocedure->register_id = $result['register_id'];
          $registerprocedure->procedure_justification_id = $result['procedure_justification_id'];
          $registerprocedure->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($registerprocedure,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $registerprocedure = RegisterProcedure::where('id',$result['id'])->update([
             'date'=>$result['date'],
             'register_id'=>$result['register_id'],
             'procedure_justification_id'=>$result['procedure_justification_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($registerprocedure,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return RegisterProcedure::destroy($id);
    }

    function backup(Request $data)
    {
       $registerprocedures = RegisterProcedure::get();
       $toReturn = [];
       foreach( $registerprocedures as $registerprocedure) {
          $attach = [];
          array_push($toReturn, ["RegisterProcedure"=>$registerprocedure, "attach"=>$attach]);
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
         $result = $row['RegisterProcedure'];
         $exist = RegisterProcedure::where('id',$result['id'])->first();
         if ($exist) {
           RegisterProcedure::where('id', $result['id'])->update([
             'date'=>$result['date'],
             'register_id'=>$result['register_id'],
             'procedure_justification_id'=>$result['procedure_justification_id'],
           ]);
         } else {
          $registerprocedure = new RegisterProcedure();
          $registerprocedure->id = $result['id'];
          $registerprocedure->date = $result['date'];
          $registerprocedure->register_id = $result['register_id'];
          $registerprocedure->procedure_justification_id = $result['procedure_justification_id'];
          $registerprocedure->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}