<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\GroupGiven;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GroupGivenController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(GroupGiven::get(),200);
       } else {
          $groupgiven = GroupGiven::findOrFail($id);
          $attach = [];
          return response()->json(["GroupGiven"=>$groupgiven, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(GroupGiven::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $groupgiven = new GroupGiven();
          $lastGroupGiven = GroupGiven::orderBy('id')->get()->last();
          if($lastGroupGiven) {
             $groupgiven->id = $lastGroupGiven->id + 1;
          } else {
             $groupgiven->id = 1;
          }
          $groupgiven->register_code = $result['register_code'];
          $groupgiven->ruc_id = $result['ruc_id'];
          $groupgiven->person_representative_id = $result['person_representative_id'];
          $groupgiven->group_type_id = $result['group_type_id'];
          $groupgiven->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($groupgiven,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $groupgiven = GroupGiven::where('id',$result['id'])->update([
             'register_code'=>$result['register_code'],
             'ruc_id'=>$result['ruc_id'],
             'person_representative_id'=>$result['person_representative_id'],
             'group_type_id'=>$result['group_type_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($groupgiven,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return GroupGiven::destroy($id);
    }

    function backup(Request $data)
    {
       $groupgivens = GroupGiven::get();
       $toReturn = [];
       foreach( $groupgivens as $groupgiven) {
          $attach = [];
          array_push($toReturn, ["GroupGiven"=>$groupgiven, "attach"=>$attach]);
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
         $result = $row['GroupGiven'];
         $exist = GroupGiven::where('id',$result['id'])->first();
         if ($exist) {
           GroupGiven::where('id', $result['id'])->update([
             'register_code'=>$result['register_code'],
             'ruc_id'=>$result['ruc_id'],
             'person_representative_id'=>$result['person_representative_id'],
             'group_type_id'=>$result['group_type_id'],
           ]);
         } else {
          $groupgiven = new GroupGiven();
          $groupgiven->id = $result['id'];
          $groupgiven->register_code = $result['register_code'];
          $groupgiven->ruc_id = $result['ruc_id'];
          $groupgiven->person_representative_id = $result['person_representative_id'];
          $groupgiven->group_type_id = $result['group_type_id'];
          $groupgiven->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}