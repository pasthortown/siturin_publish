<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\AuthLocation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthLocationController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(AuthLocation::get(),200);
       } else {
          $authlocation = AuthLocation::findOrFail($id);
          $attach = [];
          return response()->json(["AuthLocation"=>$authlocation, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(AuthLocation::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $authlocation = new AuthLocation();
          $lastAuthLocation = AuthLocation::orderBy('id')->get()->last();
          if($lastAuthLocation) {
             $authlocation->id = $lastAuthLocation->id + 1;
          } else {
             $authlocation->id = 1;
          }
          $authlocation->id_ubication = $result['id_ubication'];
          $authlocation->id_user = $result['id_user'];
          $authlocation->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($authlocation,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $authlocation = AuthLocation::where('id',$result['id'])->update([
             'id_ubication'=>$result['id_ubication'],
             'id_user'=>$result['id_user'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($authlocation,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return AuthLocation::destroy($id);
    }

    function backup(Request $data)
    {
       $authlocations = AuthLocation::get();
       $toReturn = [];
       foreach( $authlocations as $authlocation) {
          $attach = [];
          array_push($toReturn, ["AuthLocation"=>$authlocation, "attach"=>$attach]);
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
         $result = $row['AuthLocation'];
         $exist = AuthLocation::where('id',$result['id'])->first();
         if ($exist) {
           AuthLocation::where('id', $result['id'])->update([
             'id_ubication'=>$result['id_ubication'],
             'id_user'=>$result['id_user'],
           ]);
         } else {
          $authlocation = new AuthLocation();
          $authlocation->id = $result['id'];
          $authlocation->id_ubication = $result['id_ubication'];
          $authlocation->id_user = $result['id_user'];
          $authlocation->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}