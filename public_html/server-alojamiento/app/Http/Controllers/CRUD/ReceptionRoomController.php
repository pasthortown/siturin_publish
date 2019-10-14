<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\ReceptionRoom;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReceptionRoomController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(ReceptionRoom::get(),200);
       } else {
          $receptionroom = ReceptionRoom::findOrFail($id);
          $attach = [];
          return response()->json(["ReceptionRoom"=>$receptionroom, "attach"=>$attach],200);
       }
    }

    function get_by_register_id(Request $data) {
      $register_id = $data['id'];
      return response()->json(ReceptionRoom::where('register_id', $register_id)->first(),200);
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(ReceptionRoom::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $receptionroom = new ReceptionRoom();
          $lastReceptionRoom = ReceptionRoom::orderBy('id')->get()->last();
          if($lastReceptionRoom) {
             $receptionroom->id = $lastReceptionRoom->id + 1;
          } else {
             $receptionroom->id = 1;
          }
          $receptionroom->quantity = $result['quantity'];
          $receptionroom->fullfill = $result['fullfill'];
          $receptionroom->register_id = $result['register_id'];
          $receptionroom->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($receptionroom,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $receptionroom = ReceptionRoom::where('id',$result['id'])->update([
             'quantity'=>$result['quantity'],
             'fullfill'=>$result['fullfill'],
             'register_id'=>$result['register_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($receptionroom,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return ReceptionRoom::destroy($id);
    }

    function backup(Request $data)
    {
       $receptionrooms = ReceptionRoom::get();
       $toReturn = [];
       foreach( $receptionrooms as $receptionroom) {
          $attach = [];
          array_push($toReturn, ["ReceptionRoom"=>$receptionroom, "attach"=>$attach]);
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
         $result = $row['ReceptionRoom'];
         $exist = ReceptionRoom::where('id',$result['id'])->first();
         if ($exist) {
           ReceptionRoom::where('id', $result['id'])->update([
             'quantity'=>$result['quantity'],
             'fullfill'=>$result['fullfill'],
             'register_id'=>$result['register_id'],
           ]);
         } else {
          $receptionroom = new ReceptionRoom();
          $receptionroom->id = $result['id'];
          $receptionroom->quantity = $result['quantity'];
          $receptionroom->fullfill = $result['fullfill'];
          $receptionroom->register_id = $result['register_id'];
          $receptionroom->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}