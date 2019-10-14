<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Group;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GroupController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Group::get(),200);
       } else {
          $group = Group::findOrFail($id);
          $attach = [];
          return response()->json(["Group"=>$group, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Group::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $group = new Group();
          $lastGroup = Group::orderBy('id')->get()->last();
          if($lastGroup) {
             $group->id = $lastGroup->id + 1;
          } else {
             $group->id = 1;
          }
          $group->quantity = $result['quantity'];
          $group->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($group,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $group = Group::where('id',$result['id'])->update([
             'quantity'=>$result['quantity'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($group,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Group::destroy($id);
    }

    function backup(Request $data)
    {
       $groups = Group::get();
       $toReturn = [];
       foreach( $groups as $group) {
          $attach = [];
          array_push($toReturn, ["Group"=>$group, "attach"=>$attach]);
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
         $result = $row['Group'];
         $exist = Group::where('id',$result['id'])->first();
         if ($exist) {
           Group::where('id', $result['id'])->update([
             'quantity'=>$result['quantity'],
           ]);
         } else {
          $group = new Group();
          $group->id = $result['id'];
          $group->quantity = $result['quantity'];
          $group->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}