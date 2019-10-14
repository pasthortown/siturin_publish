<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\RegisterState;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegisterStateController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(RegisterState::get(),200);
       } else {
          $registerstate = RegisterState::findOrFail($id);
          $attach = [];
          return response()->json(["RegisterState"=>$registerstate, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(RegisterState::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $registerstate = new RegisterState();
          $lastRegisterState = RegisterState::orderBy('id')->get()->last();
          if($lastRegisterState) {
             $registerstate->id = $lastRegisterState->id + 1;
          } else {
             $registerstate->id = 1;
          }
          $registerstate->justification = $result['justification'];
          $registerstate->register_id = $result['register_id'];
          $registerstate->state_id = $result['state_id'];
          $registerstate->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($registerstate,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $registerstate = RegisterState::where('id',$result['id'])->update([
             'justification'=>$result['justification'],
             'register_id'=>$result['register_id'],
             'state_id'=>$result['state_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($registerstate,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return RegisterState::destroy($id);
    }

    function backup(Request $data)
    {
       $registerstates = RegisterState::get();
       $toReturn = [];
       foreach( $registerstates as $registerstate) {
          $attach = [];
          array_push($toReturn, ["RegisterState"=>$registerstate, "attach"=>$attach]);
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
         $result = $row['RegisterState'];
         $exist = RegisterState::where('id',$result['id'])->first();
         if ($exist) {
           RegisterState::where('id', $result['id'])->update([
             'justification'=>$result['justification'],
             'register_id'=>$result['register_id'],
             'state_id'=>$result['state_id'],
           ]);
         } else {
          $registerstate = new RegisterState();
          $registerstate->id = $result['id'];
          $registerstate->justification = $result['justification'];
          $registerstate->register_id = $result['register_id'];
          $registerstate->state_id = $result['state_id'];
          $registerstate->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}