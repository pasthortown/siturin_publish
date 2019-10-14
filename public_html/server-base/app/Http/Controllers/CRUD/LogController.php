<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LogController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Log::get(),200);
       } else {
          $log = Log::findOrFail($id);
          $attach = [];
          return response()->json(["Log"=>$log, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Log::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $log = new Log();
          $lastLog = Log::orderBy('id')->get()->last();
          if($lastLog) {
             $log->id = $lastLog->id + 1;
          } else {
             $log->id = 1;
          }
          $log->date_time = $result['date_time'];
          $log->request = $result['request'];
          $log->user_id = $result['user_id'];
          $log->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($log,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $log = Log::where('id',$result['id'])->update([
             'date_time'=>$result['date_time'],
             'request'=>$result['request'],
             'user_id'=>$result['user_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($log,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Log::destroy($id);
    }

    function backup(Request $data)
    {
       $logs = Log::get();
       $toReturn = [];
       foreach( $logs as $log) {
          $attach = [];
          array_push($toReturn, ["Log"=>$log, "attach"=>$attach]);
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
         $result = $row['Log'];
         $exist = Log::where('id',$result['id'])->first();
         if ($exist) {
           Log::where('id', $result['id'])->update([
             'date_time'=>$result['date_time'],
             'request'=>$result['request'],
             'user_id'=>$result['user_id'],
           ]);
         } else {
          $log = new Log();
          $log->id = $result['id'];
          $log->date_time = $result['date_time'];
          $log->request = $result['request'];
          $log->user_id = $result['user_id'];
          $log->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}