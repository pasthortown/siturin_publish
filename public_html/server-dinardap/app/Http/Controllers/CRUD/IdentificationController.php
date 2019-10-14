<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Identification;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IdentificationController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Identification::get(),200);
       } else {
          $identification = Identification::findOrFail($id);
          $attach = [];
          return response()->json(["Identification"=>$identification, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Identification::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $identification = new Identification();
          $lastIdentification = Identification::orderBy('id')->get()->last();
          if($lastIdentification) {
             $identification->id = $lastIdentification->id + 1;
          } else {
             $identification->id = 1;
          }
          $identification->number = $result['number'];
          $identification->data = $result['data'];
          $identification->date = $result['date'];
          $identification->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($identification,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $identification = Identification::where('id',$result['id'])->update([
             'number'=>$result['number'],
             'data'=>$result['data'],
             'date'=>$result['date'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($identification,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Identification::destroy($id);
    }

    function backup(Request $data)
    {
       $identifications = Identification::get();
       $toReturn = [];
       foreach( $identifications as $identification) {
          $attach = [];
          array_push($toReturn, ["Identification"=>$identification, "attach"=>$attach]);
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
         $result = $row['Identification'];
         $exist = Identification::where('id',$result['id'])->first();
         if ($exist) {
           Identification::where('id', $result['id'])->update([
             'number'=>$result['number'],
             'data'=>$result['data'],
             'date'=>$result['date'],
           ]);
         } else {
          $identification = new Identification();
          $identification->id = $result['id'];
          $identification->number = $result['number'];
          $identification->data = $result['data'];
          $identification->date = $result['date'];
          $identification->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}