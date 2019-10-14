<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\RucNameType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RucNameTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(RucNameType::get(),200);
       } else {
          $rucnametype = RucNameType::findOrFail($id);
          $attach = [];
          return response()->json(["RucNameType"=>$rucnametype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(RucNameType::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $rucnametype = new RucNameType();
          $lastRucNameType = RucNameType::orderBy('id')->get()->last();
          if($lastRucNameType) {
             $rucnametype->id = $lastRucNameType->id + 1;
          } else {
             $rucnametype->id = 1;
          }
          $rucnametype->name = $result['name'];
          $rucnametype->description = $result['description'];
          $rucnametype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($rucnametype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $rucnametype = RucNameType::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($rucnametype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return RucNameType::destroy($id);
    }

    function backup(Request $data)
    {
       $rucnametypes = RucNameType::get();
       $toReturn = [];
       foreach( $rucnametypes as $rucnametype) {
          $attach = [];
          array_push($toReturn, ["RucNameType"=>$rucnametype, "attach"=>$attach]);
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
         $result = $row['RucNameType'];
         $exist = RucNameType::where('id',$result['id'])->first();
         if ($exist) {
           RucNameType::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
           ]);
         } else {
          $rucnametype = new RucNameType();
          $rucnametype->id = $result['id'];
          $rucnametype->name = $result['name'];
          $rucnametype->description = $result['description'];
          $rucnametype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}