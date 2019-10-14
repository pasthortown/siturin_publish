<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\GroupType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GroupTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(GroupType::get(),200);
       } else {
          $grouptype = GroupType::findOrFail($id);
          $attach = [];
          return response()->json(["GroupType"=>$grouptype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(GroupType::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $grouptype = new GroupType();
          $lastGroupType = GroupType::orderBy('id')->get()->last();
          if($lastGroupType) {
             $grouptype->id = $lastGroupType->id + 1;
          } else {
             $grouptype->id = 1;
          }
          $grouptype->name = $result['name'];
          $grouptype->description = $result['description'];
          $grouptype->representative_rol_name = $result['representative_rol_name'];
          $grouptype->representative_rol_description = $result['representative_rol_description'];
          $grouptype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($grouptype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $grouptype = GroupType::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'representative_rol_name'=>$result['representative_rol_name'],
             'representative_rol_description'=>$result['representative_rol_description'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($grouptype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return GroupType::destroy($id);
    }

    function backup(Request $data)
    {
       $grouptypes = GroupType::get();
       $toReturn = [];
       foreach( $grouptypes as $grouptype) {
          $attach = [];
          array_push($toReturn, ["GroupType"=>$grouptype, "attach"=>$attach]);
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
         $result = $row['GroupType'];
         $exist = GroupType::where('id',$result['id'])->first();
         if ($exist) {
           GroupType::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'representative_rol_name'=>$result['representative_rol_name'],
             'representative_rol_description'=>$result['representative_rol_description'],
           ]);
         } else {
          $grouptype = new GroupType();
          $grouptype->id = $result['id'];
          $grouptype->name = $result['name'];
          $grouptype->description = $result['description'];
          $grouptype->representative_rol_name = $result['representative_rol_name'];
          $grouptype->representative_rol_description = $result['representative_rol_description'];
          $grouptype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}