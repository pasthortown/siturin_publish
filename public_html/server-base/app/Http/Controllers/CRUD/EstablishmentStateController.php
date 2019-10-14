<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\EstablishmentState;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EstablishmentStateController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(EstablishmentState::get(),200);
       } else {
          $establishmentstate = EstablishmentState::findOrFail($id);
          $attach = [];
          return response()->json(["EstablishmentState"=>$establishmentstate, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(EstablishmentState::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentstate = new EstablishmentState();
          $lastEstablishmentState = EstablishmentState::orderBy('id')->get()->last();
          if($lastEstablishmentState) {
             $establishmentstate->id = $lastEstablishmentState->id + 1;
          } else {
             $establishmentstate->id = 1;
          }
          $establishmentstate->justification = $result['justification'];
          $establishmentstate->state_id = $result['state_id'];
          $establishmentstate->establishment_id = $result['establishment_id'];
          $establishmentstate->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentstate,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentstate = EstablishmentState::where('id',$result['id'])->update([
             'justification'=>$result['justification'],
             'state_id'=>$result['state_id'],
             'establishment_id'=>$result['establishment_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentstate,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return EstablishmentState::destroy($id);
    }

    function backup(Request $data)
    {
       $establishmentstates = EstablishmentState::get();
       $toReturn = [];
       foreach( $establishmentstates as $establishmentstate) {
          $attach = [];
          array_push($toReturn, ["EstablishmentState"=>$establishmentstate, "attach"=>$attach]);
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
         $result = $row['EstablishmentState'];
         $exist = EstablishmentState::where('id',$result['id'])->first();
         if ($exist) {
           EstablishmentState::where('id', $result['id'])->update([
             'justification'=>$result['justification'],
             'state_id'=>$result['state_id'],
             'establishment_id'=>$result['establishment_id'],
           ]);
         } else {
          $establishmentstate = new EstablishmentState();
          $establishmentstate->id = $result['id'];
          $establishmentstate->justification = $result['justification'];
          $establishmentstate->state_id = $result['state_id'];
          $establishmentstate->establishment_id = $result['establishment_id'];
          $establishmentstate->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}