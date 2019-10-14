<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\ComplementaryServiceFoodType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ComplementaryServiceFoodTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(ComplementaryServiceFoodType::get(),200);
       } else {
          $complementaryservicefoodtype = ComplementaryServiceFoodType::findOrFail($id);
          $attach = [];
          return response()->json(["ComplementaryServiceFoodType"=>$complementaryservicefoodtype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(ComplementaryServiceFoodType::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $complementaryservicefoodtype = new ComplementaryServiceFoodType();
          $lastComplementaryServiceFoodType = ComplementaryServiceFoodType::orderBy('id')->get()->last();
          if($lastComplementaryServiceFoodType) {
             $complementaryservicefoodtype->id = $lastComplementaryServiceFoodType->id + 1;
          } else {
             $complementaryservicefoodtype->id = 1;
          }
          $complementaryservicefoodtype->name = $result['name'];
          $complementaryservicefoodtype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($complementaryservicefoodtype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $complementaryservicefoodtype = ComplementaryServiceFoodType::where('id',$result['id'])->update([
             'name'=>$result['name'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($complementaryservicefoodtype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return ComplementaryServiceFoodType::destroy($id);
    }

    function backup(Request $data)
    {
       $complementaryservicefoodtypes = ComplementaryServiceFoodType::get();
       $toReturn = [];
       foreach( $complementaryservicefoodtypes as $complementaryservicefoodtype) {
          $attach = [];
          array_push($toReturn, ["ComplementaryServiceFoodType"=>$complementaryservicefoodtype, "attach"=>$attach]);
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
         $result = $row['ComplementaryServiceFoodType'];
         $exist = ComplementaryServiceFoodType::where('id',$result['id'])->first();
         if ($exist) {
           ComplementaryServiceFoodType::where('id', $result['id'])->update([
             'name'=>$result['name'],
           ]);
         } else {
          $complementaryservicefoodtype = new ComplementaryServiceFoodType();
          $complementaryservicefoodtype->id = $result['id'];
          $complementaryservicefoodtype->name = $result['name'];
          $complementaryservicefoodtype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}