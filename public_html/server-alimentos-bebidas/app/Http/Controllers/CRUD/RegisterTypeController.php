<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\RegisterType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegisterTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(RegisterType::get(),200);
       } else {
          $registertype = RegisterType::findOrFail($id);
          $attach = [];
          return response()->json(["RegisterType"=>$registertype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(RegisterType::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $registertype = new RegisterType();
          $lastRegisterType = RegisterType::orderBy('id')->get()->last();
          if($lastRegisterType) {
             $registertype->id = $lastRegisterType->id + 1;
          } else {
             $registertype->id = 1;
          }
          $registertype->name = $result['name'];
          $registertype->description = $result['description'];
          $registertype->code = $result['code'];
          $registertype->father_code = $result['father_code'];
          $registertype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($registertype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $registertype = RegisterType::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($registertype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return RegisterType::destroy($id);
    }

    function backup(Request $data)
    {
       $registertypes = RegisterType::get();
       $toReturn = [];
       foreach( $registertypes as $registertype) {
          $attach = [];
          array_push($toReturn, ["RegisterType"=>$registertype, "attach"=>$attach]);
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
         $result = $row['RegisterType'];
         $exist = RegisterType::where('id',$result['id'])->first();
         if ($exist) {
           RegisterType::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
           ]);
         } else {
          $registertype = new RegisterType();
          $registertype->id = $result['id'];
          $registertype->name = $result['name'];
          $registertype->description = $result['description'];
          $registertype->code = $result['code'];
          $registertype->father_code = $result['father_code'];
          $registertype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}