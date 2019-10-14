<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Bed;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BedController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Bed::get(),200);
       } else {
          $bed = Bed::findOrFail($id);
          $attach = [];
          return response()->json(["Bed"=>$bed, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Bed::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $bed = new Bed();
          $lastBed = Bed::orderBy('id')->get()->last();
          if($lastBed) {
             $bed->id = $lastBed->id + 1;
          } else {
             $bed->id = 1;
          }
          $bed->quantity = $result['quantity'];
          $bed->bed_type_id = $result['bed_type_id'];
          $bed->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($bed,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $bed = Bed::where('id',$result['id'])->update([
             'quantity'=>$result['quantity'],
             'bed_type_id'=>$result['bed_type_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($bed,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Bed::destroy($id);
    }

    function backup(Request $data)
    {
       $beds = Bed::get();
       $toReturn = [];
       foreach( $beds as $bed) {
          $attach = [];
          array_push($toReturn, ["Bed"=>$bed, "attach"=>$attach]);
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
         $result = $row['Bed'];
         $exist = Bed::where('id',$result['id'])->first();
         if ($exist) {
           Bed::where('id', $result['id'])->update([
             'quantity'=>$result['quantity'],
             'bed_type_id'=>$result['bed_type_id'],
           ]);
         } else {
          $bed = new Bed();
          $bed->id = $result['id'];
          $bed->quantity = $result['quantity'];
          $bed->bed_type_id = $result['bed_type_id'];
          $bed->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}