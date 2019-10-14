<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Approval;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApprovalController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Approval::get(),200);
       } else {
          $approval = Approval::findOrFail($id);
          $attach = [];
          return response()->json(["Approval"=>$approval, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Approval::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $approval = new Approval();
          $lastApproval = Approval::orderBy('id')->get()->last();
          if($lastApproval) {
             $approval->id = $lastApproval->id + 1;
          } else {
             $approval->id = 1;
          }
          $approval->name = $result['name'];
          $approval->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($approval,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $approval = Approval::where('id',$result['id'])->update([
             'name'=>$result['name'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($approval,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Approval::destroy($id);
    }

    function backup(Request $data)
    {
       $approvals = Approval::get();
       $toReturn = [];
       foreach( $approvals as $approval) {
          $attach = [];
          array_push($toReturn, ["Approval"=>$approval, "attach"=>$attach]);
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
         $result = $row['Approval'];
         $exist = Approval::where('id',$result['id'])->first();
         if ($exist) {
           Approval::where('id', $result['id'])->update([
             'name'=>$result['name'],
           ]);
         } else {
          $approval = new Approval();
          $approval->id = $result['id'];
          $approval->name = $result['name'];
          $approval->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}