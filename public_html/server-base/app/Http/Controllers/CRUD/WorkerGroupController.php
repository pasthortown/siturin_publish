<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\WorkerGroup;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WorkerGroupController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(WorkerGroup::get(),200);
       } else {
          $workergroup = WorkerGroup::findOrFail($id);
          $attach = [];
          return response()->json(["WorkerGroup"=>$workergroup, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(WorkerGroup::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $workergroup = new WorkerGroup();
          $lastWorkerGroup = WorkerGroup::orderBy('id')->get()->last();
          if($lastWorkerGroup) {
             $workergroup->id = $lastWorkerGroup->id + 1;
          } else {
             $workergroup->id = 1;
          }
          $workergroup->name = $result['name'];
          $workergroup->description = $result['description'];
          $workergroup->is_max = $result['is_max'];
          $workergroup->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($workergroup,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $workergroup = WorkerGroup::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'is_max'=>$result['is_max'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($workergroup,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return WorkerGroup::destroy($id);
    }

    function backup(Request $data)
    {
       $workergroups = WorkerGroup::get();
       $toReturn = [];
       foreach( $workergroups as $workergroup) {
          $attach = [];
          array_push($toReturn, ["WorkerGroup"=>$workergroup, "attach"=>$attach]);
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
         $result = $row['WorkerGroup'];
         $exist = WorkerGroup::where('id',$result['id'])->first();
         if ($exist) {
           WorkerGroup::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'is_max'=>$result['is_max'],
           ]);
         } else {
          $workergroup = new WorkerGroup();
          $workergroup->id = $result['id'];
          $workergroup->name = $result['name'];
          $workergroup->description = $result['description'];
          $workergroup->is_max = $result['is_max'];
          $workergroup->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}