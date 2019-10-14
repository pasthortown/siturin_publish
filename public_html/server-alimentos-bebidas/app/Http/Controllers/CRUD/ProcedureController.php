<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Procedure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProcedureController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Procedure::get(),200);
       } else {
          $procedure = Procedure::findOrFail($id);
          $attach = [];
          return response()->json(["Procedure"=>$procedure, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Procedure::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $procedure = new Procedure();
          $lastProcedure = Procedure::orderBy('id')->get()->last();
          if($lastProcedure) {
             $procedure->id = $lastProcedure->id + 1;
          } else {
             $procedure->id = 1;
          }
          $procedure->name = $result['name'];
          $procedure->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($procedure,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $procedure = Procedure::where('id',$result['id'])->update([
             'name'=>$result['name'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($procedure,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Procedure::destroy($id);
    }

    function backup(Request $data)
    {
       $procedures = Procedure::get();
       $toReturn = [];
       foreach( $procedures as $procedure) {
          $attach = [];
          array_push($toReturn, ["Procedure"=>$procedure, "attach"=>$attach]);
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
         $result = $row['Procedure'];
         $exist = Procedure::where('id',$result['id'])->first();
         if ($exist) {
           Procedure::where('id', $result['id'])->update([
             'name'=>$result['name'],
           ]);
         } else {
          $procedure = new Procedure();
          $procedure->id = $result['id'];
          $procedure->name = $result['name'];
          $procedure->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}