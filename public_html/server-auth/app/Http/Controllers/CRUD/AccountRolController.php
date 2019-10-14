<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
Use Exception;
use App\AccountRol;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Crypt;

class AccountRolController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(AccountRol::get(),200);
       } else {
          $accountrol = AccountRol::findOrFail($id);
          $attach = [];
          return response()->json(["AccountRol"=>$accountrol, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(AccountRol::paginate($size),200);
    }

    function filtered(Request $data)
    {
       $size = $data['size'];
       $filter = $data['filter'];
       if($filter === 'all') {
         return response()->json(AccountRol::paginate($size),200);
       } else {
         return response()->json(AccountRol::where('father_code', $filter)->paginate($size),200);
       }
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $accountrol = new AccountRol();
          $lastAccountRol = AccountRol::orderBy('id')->get()->last();
          if($lastAccountRol) {
             $accountrol->id = $lastAccountRol->id + 1;
          } else {
             $accountrol->id = 1;
          }
          $accountrol->name = $result['name'];
          $accountrol->description = $result['description'];
          $accountrol->code = $result['code'];
          $accountrol->father_code = $result['father_code'];
          $accountrol->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($accountrol,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $accountrol = AccountRol::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($accountrol,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return AccountRol::destroy($id);
    }

    function backup(Request $data)
    {
       $accountrols = AccountRol::get();
       $toReturn = [];
       foreach( $accountrols as $accountrol) {
          $attach = [];
          array_push($toReturn, ["AccountRol"=>$accountrol, "attach"=>$attach]);
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
         $result = $row['AccountRol'];
         $exist = AccountRol::where('id',$result['id'])->first();
         if ($exist) {
           AccountRol::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
           ]);
         } else {
          $accountrol = new AccountRol();
          $accountrol->id = $result['id'];
          $accountrol->name = $result['name'];
          $accountrol->description = $result['description'];
          $accountrol->code = $result['code'];
          $accountrol->father_code = $result['father_code'];
          $accountrol->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}
