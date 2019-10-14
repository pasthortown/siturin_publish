<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Worker;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WorkerController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Worker::get(),200);
       } else {
          $worker = Worker::findOrFail($id);
          $attach = [];
          return response()->json(["Worker"=>$worker, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Worker::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $worker = new Worker();
          $lastWorker = Worker::orderBy('id')->get()->last();
          if($lastWorker) {
             $worker->id = $lastWorker->id + 1;
          } else {
             $worker->id = 1;
          }
          $worker->count = $result['count'];
          $worker->gender_id = $result['gender_id'];
          $worker->worker_group_id = $result['worker_group_id'];
          $worker->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($worker,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $worker = Worker::where('id',$result['id'])->update([
             'count'=>$result['count'],
             'gender_id'=>$result['gender_id'],
             'worker_group_id'=>$result['worker_group_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($worker,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Worker::destroy($id);
    }

    function backup(Request $data)
    {
       $workers = Worker::get();
       $toReturn = [];
       foreach( $workers as $worker) {
          $attach = [];
          array_push($toReturn, ["Worker"=>$worker, "attach"=>$attach]);
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
         $result = $row['Worker'];
         $exist = Worker::where('id',$result['id'])->first();
         if ($exist) {
           Worker::where('id', $result['id'])->update([
             'count'=>$result['count'],
             'gender_id'=>$result['gender_id'],
             'worker_group_id'=>$result['worker_group_id'],
           ]);
         } else {
          $worker = new Worker();
          $worker->id = $result['id'];
          $worker->count = $result['count'];
          $worker->gender_id = $result['gender_id'];
          $worker->worker_group_id = $result['worker_group_id'];
          $worker->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}