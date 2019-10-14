<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\SystemName;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SystemNameController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(SystemName::get(),200);
       } else {
          $systemname = SystemName::findOrFail($id);
          $attach = [];
          return response()->json(["SystemName"=>$systemname, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(SystemName::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $systemname = new SystemName();
          $lastSystemName = SystemName::orderBy('id')->get()->last();
          if($lastSystemName) {
             $systemname->id = $lastSystemName->id + 1;
          } else {
             $systemname->id = 1;
          }
          $systemname->name = $result['name'];
          $systemname->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($systemname,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $systemname = SystemName::where('id',$result['id'])->update([
             'name'=>$result['name'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($systemname,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return SystemName::destroy($id);
    }

    function backup(Request $data)
    {
       $systemnames = SystemName::get();
       $toReturn = [];
       foreach( $systemnames as $systemname) {
          $attach = [];
          array_push($toReturn, ["SystemName"=>$systemname, "attach"=>$attach]);
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
         $result = $row['SystemName'];
         $exist = SystemName::where('id',$result['id'])->first();
         if ($exist) {
           SystemName::where('id', $result['id'])->update([
             'name'=>$result['name'],
           ]);
         } else {
          $systemname = new SystemName();
          $systemname->id = $result['id'];
          $systemname->name = $result['name'];
          $systemname->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}