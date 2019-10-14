<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Agreement;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AgreementController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Agreement::get(),200);
       } else {
          $agreement = Agreement::findOrFail($id);
          $attach = [];
          return response()->json(["Agreement"=>$agreement, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Agreement::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $agreement = new Agreement();
          $lastAgreement = Agreement::orderBy('id')->get()->last();
          if($lastAgreement) {
             $agreement->id = $lastAgreement->id + 1;
          } else {
             $agreement->id = 1;
          }
          $agreement->title = $result['title'];
          $agreement->content = $result['content'];
          $agreement->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($agreement,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $agreement = Agreement::where('id',$result['id'])->update([
             'title'=>$result['title'],
             'content'=>$result['content'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($agreement,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Agreement::destroy($id);
    }

    function backup(Request $data)
    {
       $agreements = Agreement::get();
       $toReturn = [];
       foreach( $agreements as $agreement) {
          $attach = [];
          array_push($toReturn, ["Agreement"=>$agreement, "attach"=>$attach]);
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
         $result = $row['Agreement'];
         $exist = Agreement::where('id',$result['id'])->first();
         if ($exist) {
           Agreement::where('id', $result['id'])->update([
             'title'=>$result['title'],
             'content'=>$result['content'],
           ]);
         } else {
          $agreement = new Agreement();
          $agreement->id = $result['id'];
          $agreement->title = $result['title'];
          $agreement->content = $result['content'];
          $agreement->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}