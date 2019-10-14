<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Gender;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GenderController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Gender::get(),200);
       } else {
          $gender = Gender::findOrFail($id);
          $attach = [];
          return response()->json(["Gender"=>$gender, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Gender::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $gender = new Gender();
          $lastGender = Gender::orderBy('id')->get()->last();
          if($lastGender) {
             $gender->id = $lastGender->id + 1;
          } else {
             $gender->id = 1;
          }
          $gender->name = $result['name'];
          $gender->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($gender,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $gender = Gender::where('id',$result['id'])->update([
             'name'=>$result['name'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($gender,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Gender::destroy($id);
    }

    function backup(Request $data)
    {
       $genders = Gender::get();
       $toReturn = [];
       foreach( $genders as $gender) {
          $attach = [];
          array_push($toReturn, ["Gender"=>$gender, "attach"=>$attach]);
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
         $result = $row['Gender'];
         $exist = Gender::where('id',$result['id'])->first();
         if ($exist) {
           Gender::where('id', $result['id'])->update([
             'name'=>$result['name'],
           ]);
         } else {
          $gender = new Gender();
          $gender->id = $result['id'];
          $gender->name = $result['name'];
          $gender->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}