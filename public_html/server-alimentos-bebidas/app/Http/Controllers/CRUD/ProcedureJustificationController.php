<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\ProcedureJustification;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProcedureJustificationController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(ProcedureJustification::get(),200);
       } else {
          $procedurejustification = ProcedureJustification::findOrFail($id);
          $attach = [];
          return response()->json(["ProcedureJustification"=>$procedurejustification, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(ProcedureJustification::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $procedurejustification = new ProcedureJustification();
          $lastProcedureJustification = ProcedureJustification::orderBy('id')->get()->last();
          if($lastProcedureJustification) {
             $procedurejustification->id = $lastProcedureJustification->id + 1;
          } else {
             $procedurejustification->id = 1;
          }
          $procedurejustification->justification = $result['justification'];
          $procedurejustification->procedure_id = $result['procedure_id'];
          $procedurejustification->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($procedurejustification,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $procedurejustification = ProcedureJustification::where('id',$result['id'])->update([
             'justification'=>$result['justification'],
             'procedure_id'=>$result['procedure_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($procedurejustification,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return ProcedureJustification::destroy($id);
    }

    function backup(Request $data)
    {
       $procedurejustifications = ProcedureJustification::get();
       $toReturn = [];
       foreach( $procedurejustifications as $procedurejustification) {
          $attach = [];
          array_push($toReturn, ["ProcedureJustification"=>$procedurejustification, "attach"=>$attach]);
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
         $result = $row['ProcedureJustification'];
         $exist = ProcedureJustification::where('id',$result['id'])->first();
         if ($exist) {
           ProcedureJustification::where('id', $result['id'])->update([
             'justification'=>$result['justification'],
             'procedure_id'=>$result['procedure_id'],
           ]);
         } else {
          $procedurejustification = new ProcedureJustification();
          $procedurejustification->id = $result['id'];
          $procedurejustification->justification = $result['justification'];
          $procedurejustification->procedure_id = $result['procedure_id'];
          $procedurejustification->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}