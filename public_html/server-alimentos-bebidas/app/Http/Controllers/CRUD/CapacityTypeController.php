<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\CapacityType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CapacityTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(CapacityType::get(),200);
       } else {
          $capacitytype = CapacityType::findOrFail($id);
          $attach = [];
          return response()->json(["CapacityType"=>$capacitytype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(CapacityType::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $capacitytype = new CapacityType();
          $lastCapacityType = CapacityType::orderBy('id')->get()->last();
          if($lastCapacityType) {
             $capacitytype->id = $lastCapacityType->id + 1;
          } else {
             $capacitytype->id = 1;
          }
          $capacitytype->name = $result['name'];
          $capacitytype->description = $result['description'];
          $capacitytype->group_quantity = $result['group_quantity'];
          $capacitytype->is_island = $result['is_island'];
          $capacitytype->spaces = $result['spaces'];
          $capacitytype->editable_groups = $result['editable_groups'];
          $capacitytype->editable_spaces = $result['editable_spaces'];
          $capacitytype->register_type_id = $result['register_type_id'];
          $capacitytype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($capacitytype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $capacitytype = CapacityType::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'group_quantity'=>$result['group_quantity'],
             'is_island'=>$result['is_island'],
             'spaces'=>$result['spaces'],
             'editable_groups'=>$result['editable_groups'],
             'editable_spaces'=>$result['editable_spaces'],
             'register_type_id'=>$result['register_type_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($capacitytype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return CapacityType::destroy($id);
    }

    function backup(Request $data)
    {
       $capacitytypes = CapacityType::get();
       $toReturn = [];
       foreach( $capacitytypes as $capacitytype) {
          $attach = [];
          array_push($toReturn, ["CapacityType"=>$capacitytype, "attach"=>$attach]);
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
         $result = $row['CapacityType'];
         $exist = CapacityType::where('id',$result['id'])->first();
         if ($exist) {
           CapacityType::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'group_quantity'=>$result['group_quantity'],
             'is_island'=>$result['is_island'],
             'spaces'=>$result['spaces'],
             'editable_groups'=>$result['editable_groups'],
             'editable_spaces'=>$result['editable_spaces'],
             'register_type_id'=>$result['register_type_id'],
           ]);
         } else {
          $capacitytype = new CapacityType();
          $capacitytype->id = $result['id'];
          $capacitytype->name = $result['name'];
          $capacitytype->description = $result['description'];
          $capacitytype->group_quantity = $result['group_quantity'];
          $capacitytype->is_island = $result['is_island'];
          $capacitytype->spaces = $result['spaces'];
          $capacitytype->editable_groups = $result['editable_groups'];
          $capacitytype->editable_spaces = $result['editable_spaces'];
          $capacitytype->register_type_id = $result['register_type_id'];
          $capacitytype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}