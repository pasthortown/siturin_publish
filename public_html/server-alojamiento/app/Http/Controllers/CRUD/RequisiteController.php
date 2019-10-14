<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Requisite;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RequisiteController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
         return response()->json(Requisite::orderBy('id', 'ASC')->get(),200);
       } else {
          $requisite = Requisite::findOrFail($id);
          $attach = [];
          return response()->json(["Requisite"=>$requisite, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Requisite::orderBy('id', 'ASC')->paginate($size),200);
    }

    function filtered(Request $data)
    {
       $filter = $data['filter'];
       return response()->json(Requisite::where('register_type_id', $filter)->get(),200);
    }
    
    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $requisite = new Requisite();
          $lastRequisite = Requisite::orderBy('id')->get()->last();
          if($lastRequisite) {
             $requisite->id = $lastRequisite->id + 1;
          } else {
             $requisite->id = 1;
          }
          $requisite->name = $result['name'];
          $requisite->description = $result['description'];
          $requisite->father_code = $result['father_code'];
          $requisite->to_approve = $result['to_approve'];
          $requisite->mandatory = $result['mandatory'];
          $requisite->type = $result['type'];
          $requisite->params = $result['params'];
          $requisite->code = $result['code'];
          $requisite->register_type_id = $result['register_type_id'];
          $requisite->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($requisite,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $requisite = Requisite::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'father_code'=>$result['father_code'],
             'to_approve'=>$result['to_approve'],
             'mandatory'=>$result['mandatory'],
             'type'=>$result['type'],
             'params'=>$result['params'],
             'code'=>$result['code'],
             'register_type_id'=>$result['register_type_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($requisite,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Requisite::destroy($id);
    }

    function backup(Request $data)
    {
       $requisites = Requisite::get();
       $toReturn = [];
       foreach( $requisites as $requisite) {
          $attach = [];
          array_push($toReturn, ["Requisite"=>$requisite, "attach"=>$attach]);
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
         $result = $row['Requisite'];
         $exist = Requisite::where('id',$result['id'])->first();
         if ($exist) {
           Requisite::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'father_code'=>$result['father_code'],
             'to_approve'=>$result['to_approve'],
             'mandatory'=>$result['mandatory'],
             'type'=>$result['type'],
             'params'=>$result['params'],
             'code'=>$result['code'],
             'register_type_id'=>$result['register_type_id'],
           ]);
         } else {
          $requisite = new Requisite();
          $requisite->id = $result['id'];
          $requisite->name = $result['name'];
          $requisite->description = $result['description'];
          $requisite->father_code = $result['father_code'];
          $requisite->to_approve = $result['to_approve'];
          $requisite->mandatory = $result['mandatory'];
          $requisite->type = $result['type'];
          $requisite->params = $result['params'];
          $requisite->code = $result['code'];
          $requisite->register_type_id = $result['register_type_id'];
          $requisite->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}