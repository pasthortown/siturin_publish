<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Ruc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RucController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Ruc::get(),200);
       } else {
          $ruc = Ruc::findOrFail($id);
          $attach = [];
          return response()->json(["Ruc"=>$ruc, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Ruc::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $ruc = new Ruc();
          $lastRuc = Ruc::orderBy('id')->get()->last();
          if($lastRuc) {
             $ruc->id = $lastRuc->id + 1;
          } else {
             $ruc->id = 1;
          }
          $ruc->number = $result['number'];
          $ruc->data = $result['data'];
          $ruc->date = $result['date'];
          $ruc->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($ruc,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $ruc = Ruc::where('id',$result['id'])->update([
             'number'=>$result['number'],
             'data'=>$result['data'],
             'date'=>$result['date'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($ruc,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Ruc::destroy($id);
    }

    function backup(Request $data)
    {
       $rucs = Ruc::get();
       $toReturn = [];
       foreach( $rucs as $ruc) {
          $attach = [];
          array_push($toReturn, ["Ruc"=>$ruc, "attach"=>$attach]);
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
         $result = $row['Ruc'];
         $exist = Ruc::where('id',$result['id'])->first();
         if ($exist) {
           Ruc::where('id', $result['id'])->update([
             'number'=>$result['number'],
             'data'=>$result['data'],
             'date'=>$result['date'],
           ]);
         } else {
          $ruc = new Ruc();
          $ruc->id = $result['id'];
          $ruc->number = $result['number'];
          $ruc->data = $result['data'];
          $ruc->date = $result['date'];
          $ruc->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}