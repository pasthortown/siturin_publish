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
          $beds_on_capacity = $capacity->Beds()->get();
          array_push($attach, ["beds_on_capacity"=>$beds_on_capacity]);
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
          $capacity->max_beds = $result['max_beds'];
          $capacity->max_spaces = $result['max_spaces'];
          $capacity->capacity_type_id = $result['capacity_type_id'];
          $capacity->save();
          $beds_on_capacity = $result['beds_on_capacity'];
          foreach( $beds_on_capacity as $bed) {
             $capacity->Beds()->attach($bed['id']);
          }
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
             'max_beds'=>$result['max_beds'],
             'max_spaces'=>$result['max_spaces'],
             'capacity_type_id'=>$result['capacity_type_id'],
          ]);
          $capacity = Capacity::where('id',$result['id'])->first();
          $capacity = Capacity::where('id',$result['id'])->first();
          $beds_on_capacity = $result['beds_on_capacity'];
          $beds_on_capacity_old = $capacity->Beds()->get();
          foreach( $beds_on_capacity_old as $bed_old ) {
             $delete = true;
             foreach( $beds_on_capacity as $bed ) {
                if ( $bed_old->id === $bed['id'] ) {
                   $delete = false;
                }
             }
             if ( $delete ) {
                $capacity->Beds()->detach($bed_old->id);
             }
          }
          foreach( $beds_on_capacity as $bed ) {
             $add = true;
             foreach( $beds_on_capacity_old as $bed_old) {
                if ( $bed_old->id === $bed['id'] ) {
                   $add = false;
                }
             }
             if ( $add ) {
                $capacity->Beds()->attach($bed['id']);
             }
          }
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
          $beds_on_capacity = $capacity->Beds()->get();
          array_push($attach, ["beds_on_capacity"=>$beds_on_capacity]);
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
             'capacity_type_id'=>$result['capacity_type_id'],
             'max_beds'=>$result['max_beds'],
             'max_spaces'=>$result['max_spaces'],
           ]);
         } else {
          $capacity = new Capacity();
          $capacity->id = $result['id'];
          $capacity->quantity = $result['quantity'];
          $capacity->max_beds = $result['max_beds'];
          $capacity->max_spaces = $result['max_spaces'];
          $capacity->capacity_type_id = $result['capacity_type_id'];
          $capacity->save();
         }
         $capacity = Capacity::where('id',$result['id'])->first();
         $capacity = Capacity::where('id',$result['id'])->first();
         $beds_on_capacity = [];
         foreach($row['attach'] as $attach){
            $beds_on_capacity = $attach['beds_on_capacity'];
         }
         $beds_on_capacity_old = $capacity->Beds()->get();
         foreach( $beds_on_capacity_old as $bed_old ) {
            $delete = true;
            foreach( $beds_on_capacity as $bed ) {
               if ( $bed_old->id === $bed['id'] ) {
                  $delete = false;
               }
            }
            if ( $delete ) {
               $capacity->Beds()->detach($bed_old->id);
            }
         }
         foreach( $beds_on_capacity as $bed ) {
            $add = true;
            foreach( $beds_on_capacity_old as $bed_old) {
               if ( $bed_old->id === $bed['id'] ) {
                  $add = false;
               }
            }
            if ( $add ) {
               $capacity->Beds()->attach($bed['id']);
            }
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}