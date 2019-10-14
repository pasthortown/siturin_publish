<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\RegisterRequisite;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegisterRequisiteController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(RegisterRequisite::get(),200);
       } else {
          $registerrequisite = RegisterRequisite::findOrFail($id);
          $attach = [];
          return response()->json(["RegisterRequisite"=>$registerrequisite, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(RegisterRequisite::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $registerrequisite = new RegisterRequisite();
          $lastRegisterRequisite = RegisterRequisite::orderBy('id')->get()->last();
          if($lastRegisterRequisite) {
             $registerrequisite->id = $lastRegisterRequisite->id + 1;
          } else {
             $registerrequisite->id = 1;
          }
          $registerrequisite->fullfill = $result['fullfill'];
          $registerrequisite->value = $result['value'];
          $registerrequisite->requisite_id = $result['requisite_id'];
          $registerrequisite->register_id = $result['register_id'];
          $registerrequisite->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($registerrequisite,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $registerrequisite = RegisterRequisite::where('id',$result['id'])->update([
             'fullfill'=>$result['fullfill'],
             'value'=>$result['value'],
             'requisite_id'=>$result['requisite_id'],
             'register_id'=>$result['register_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($registerrequisite,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return RegisterRequisite::destroy($id);
    }

    function backup(Request $data)
    {
       $registerrequisites = RegisterRequisite::get();
       $toReturn = [];
       foreach( $registerrequisites as $registerrequisite) {
          $attach = [];
          array_push($toReturn, ["RegisterRequisite"=>$registerrequisite, "attach"=>$attach]);
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
         $result = $row['RegisterRequisite'];
         $exist = RegisterRequisite::where('id',$result['id'])->first();
         if ($exist) {
           RegisterRequisite::where('id', $result['id'])->update([
             'fullfill'=>$result['fullfill'],
             'value'=>$result['value'],
             'requisite_id'=>$result['requisite_id'],
             'register_id'=>$result['register_id'],
           ]);
         } else {
          $registerrequisite = new RegisterRequisite();
          $registerrequisite->id = $result['id'];
          $registerrequisite->fullfill = $result['fullfill'];
          $registerrequisite->value = $result['value'];
          $registerrequisite->requisite_id = $result['requisite_id'];
          $registerrequisite->register_id = $result['register_id'];
          $registerrequisite->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}