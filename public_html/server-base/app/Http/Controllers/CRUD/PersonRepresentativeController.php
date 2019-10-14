<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\PersonRepresentative;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PersonRepresentativeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(PersonRepresentative::get(),200);
       } else {
          $personrepresentative = PersonRepresentative::findOrFail($id);
          $attach = [];
          return response()->json(["PersonRepresentative"=>$personrepresentative, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(PersonRepresentative::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $personrepresentative = new PersonRepresentative();
          $lastPersonRepresentative = PersonRepresentative::orderBy('id')->get()->last();
          if($lastPersonRepresentative) {
             $personrepresentative->id = $lastPersonRepresentative->id + 1;
          } else {
             $personrepresentative->id = 1;
          }
          $personrepresentative->identification = $result['identification'];
          $personrepresentative->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($personrepresentative,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $personrepresentative = PersonRepresentative::where('id',$result['id'])->update([
             'identification'=>$result['identification'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($personrepresentative,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return PersonRepresentative::destroy($id);
    }

    function backup(Request $data)
    {
       $personrepresentatives = PersonRepresentative::get();
       $toReturn = [];
       foreach( $personrepresentatives as $personrepresentative) {
          $attach = [];
          array_push($toReturn, ["PersonRepresentative"=>$personrepresentative, "attach"=>$attach]);
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
         $result = $row['PersonRepresentative'];
         $exist = PersonRepresentative::where('id',$result['id'])->first();
         if ($exist) {
           PersonRepresentative::where('id', $result['id'])->update([
             'identification'=>$result['identification'],
           ]);
         } else {
          $personrepresentative = new PersonRepresentative();
          $personrepresentative->id = $result['id'];
          $personrepresentative->identification = $result['identification'];
          $personrepresentative->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}