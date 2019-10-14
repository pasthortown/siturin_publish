<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Capacity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CapacityController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Capacity::get(),200);
       } else {
          $capacity = Capacity::findOrFail($id);
          $attach = [];
          return response()->json(["Capacity"=>$capacity, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Capacity::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $capacity = new Capacity();
          $lastCapacity = Capacity::orderBy('id')->get()->last();
          if($lastCapacity) {
             $capacity->id = $lastCapacity->id + 1;
          } else {
             $capacity->id = 1;
          }
          $capacity->quantity = $result['quantity'];
          $capacity->max_groups = $result['max_groups'];
          $capacity->max_spaces = $result['max_spaces'];
          $capacity->capacity_type_id = $result['capacity_type_id'];
          $capacity->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($capacity,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $capacity = Capacity::where('id',$result['id'])->update([
             'quantity'=>$result['quantity'],
             'max_groups'=>$result['max_groups'],
             'max_spaces'=>$result['max_spaces'],
             'capacity_type_id'=>$result['capacity_type_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($capacity,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Capacity::destroy($id);
    }

    function backup(Request $data)
    {
       $capacities = Capacity::get();
       $toReturn = [];
       foreach( $capacities as $capacity) {
          $attach = [];
          array_push($toReturn, ["Capacity"=>$capacity, "attach"=>$attach]);
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
         $result = $row['Capacity'];
         $exist = Capacity::where('id',$result['id'])->first();
         if ($exist) {
           Capacity::where('id', $result['id'])->update([
             'quantity'=>$result['quantity'],
             'max_groups'=>$result['max_groups'],
             'max_spaces'=>$result['max_spaces'],
             'capacity_type_id'=>$result['capacity_type_id'],
           ]);
         } else {
          $capacity = new Capacity();
          $capacity->id = $result['id'];
          $capacity->quantity = $result['quantity'];
          $capacity->max_groups = $result['max_groups'];
          $capacity->max_spaces = $result['max_spaces'];
          $capacity->capacity_type_id = $result['capacity_type_id'];
          $capacity->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}