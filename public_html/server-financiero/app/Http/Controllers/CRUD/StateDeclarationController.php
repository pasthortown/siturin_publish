<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\StateDeclaration;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StateDeclarationController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(StateDeclaration::get(),200);
       } else {
          $statedeclaration = StateDeclaration::findOrFail($id);
          $attach = [];
          return response()->json(["StateDeclaration"=>$statedeclaration, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(StateDeclaration::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $statedeclaration = new StateDeclaration();
          $lastStateDeclaration = StateDeclaration::orderBy('id')->get()->last();
          if($lastStateDeclaration) {
             $statedeclaration->id = $lastStateDeclaration->id + 1;
          } else {
             $statedeclaration->id = 1;
          }
          $statedeclaration->justification = $result['justification'];
          $statedeclaration->moment = $result['moment'];
          $statedeclaration->declaration_id = $result['declaration_id'];
          $statedeclaration->state_id = $result['state_id'];
          $statedeclaration->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($statedeclaration,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $statedeclaration = StateDeclaration::where('id',$result['id'])->update([
             'justification'=>$result['justification'],
             'moment'=>$result['moment'],
             'declaration_id'=>$result['declaration_id'],
             'state_id'=>$result['state_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($statedeclaration,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return StateDeclaration::destroy($id);
    }

    function backup(Request $data)
    {
       $statedeclarations = StateDeclaration::get();
       $toReturn = [];
       foreach( $statedeclarations as $statedeclaration) {
          $attach = [];
          array_push($toReturn, ["StateDeclaration"=>$statedeclaration, "attach"=>$attach]);
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
         $result = $row['StateDeclaration'];
         $exist = StateDeclaration::where('id',$result['id'])->first();
         if ($exist) {
           StateDeclaration::where('id', $result['id'])->update([
             'justification'=>$result['justification'],
             'moment'=>$result['moment'],
             'declaration_id'=>$result['declaration_id'],
             'state_id'=>$result['state_id'],
           ]);
         } else {
          $statedeclaration = new StateDeclaration();
          $statedeclaration->id = $result['id'];
          $statedeclaration->justification = $result['justification'];
          $statedeclaration->moment = $result['moment'];
          $statedeclaration->declaration_id = $result['declaration_id'];
          $statedeclaration->state_id = $result['state_id'];
          $statedeclaration->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}