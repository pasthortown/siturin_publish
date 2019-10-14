<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\ComplementaryServiceFood;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ComplementaryServiceFoodController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(ComplementaryServiceFood::get(),200);
       } else {
          $complementaryservicefood = ComplementaryServiceFood::findOrFail($id);
          $attach = [];
          return response()->json(["ComplementaryServiceFood"=>$complementaryservicefood, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(ComplementaryServiceFood::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $complementaryservicefood = new ComplementaryServiceFood();
          $lastComplementaryServiceFood = ComplementaryServiceFood::orderBy('id')->get()->last();
          if($lastComplementaryServiceFood) {
             $complementaryservicefood->id = $lastComplementaryServiceFood->id + 1;
          } else {
             $complementaryservicefood->id = 1;
          }
          $complementaryservicefood->quantity_tables = $result['quantity_tables'];
          $complementaryservicefood->quantity_chairs = $result['quantity_chairs'];
          $complementaryservicefood->complementary_service_food_type_id = $result['complementary_service_food_type_id'];
          $complementaryservicefood->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($complementaryservicefood,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $complementaryservicefood = ComplementaryServiceFood::where('id',$result['id'])->update([
             'quantity_tables'=>$result['quantity_tables'],
             'quantity_chairs'=>$result['quantity_chairs'],
             'complementary_service_food_type_id'=>$result['complementary_service_food_type_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($complementaryservicefood,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return ComplementaryServiceFood::destroy($id);
    }

    function backup(Request $data)
    {
       $complementaryservicefoods = ComplementaryServiceFood::get();
       $toReturn = [];
       foreach( $complementaryservicefoods as $complementaryservicefood) {
          $attach = [];
          array_push($toReturn, ["ComplementaryServiceFood"=>$complementaryservicefood, "attach"=>$attach]);
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
         $result = $row['ComplementaryServiceFood'];
         $exist = ComplementaryServiceFood::where('id',$result['id'])->first();
         if ($exist) {
           ComplementaryServiceFood::where('id', $result['id'])->update([
             'quantity_tables'=>$result['quantity_tables'],
             'quantity_chairs'=>$result['quantity_chairs'],
             'complementary_service_food_type_id'=>$result['complementary_service_food_type_id'],
           ]);
         } else {
          $complementaryservicefood = new ComplementaryServiceFood();
          $complementaryservicefood->id = $result['id'];
          $complementaryservicefood->quantity_tables = $result['quantity_tables'];
          $complementaryservicefood->quantity_chairs = $result['quantity_chairs'];
          $complementaryservicefood->complementary_service_food_type_id = $result['complementary_service_food_type_id'];
          $complementaryservicefood->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}