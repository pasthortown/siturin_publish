<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\ApprovalState;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApprovalStateController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(ApprovalState::get(),200);
       } else {
          $approvalstate = ApprovalState::findOrFail($id);
          $attach = [];
          return response()->json(["ApprovalState"=>$approvalstate, "attach"=>$attach],200);
       }
    }

    function byRegisterId(Request $data)
    {
       $register_id = $data['register_id'];
       return response()->json(ApprovalState::where('register_id', $register_id)->get(),200);
    }
    
    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(ApprovalState::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $approvalstate = new ApprovalState();
          $lastApprovalState = ApprovalState::orderBy('id')->get()->last();
          if($lastApprovalState) {
             $approvalstate->id = $lastApprovalState->id + 1;
          } else {
             $approvalstate->id = 1;
          }
          $approvalstate->value = $result['value'];
          $approvalstate->date_assigment = $result['date_assigment'];
          $approvalstate->notes = $result['notes'];
          $approvalstate->id_user = $result['id_user'];
          $approvalstate->date_fullfill = $result['date_fullfill'];
          $approvalstate->register_id = $result['register_id'];
          $approvalstate->approval_id = $result['approval_id'];
          $approvalstate->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($approvalstate,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $approvalstate = ApprovalState::where('id',$result['id'])->update([
             'value'=>$result['value'],
             'date_assigment'=>$result['date_assigment'],
             'notes'=>$result['notes'],
             'id_user'=>$result['id_user'],
             'date_fullfill'=>$result['date_fullfill'],
             'register_id'=>$result['register_id'],
             'approval_id'=>$result['approval_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($approvalstate,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return ApprovalState::destroy($id);
    }

    function backup(Request $data)
    {
       $approvalstates = ApprovalState::get();
       $toReturn = [];
       foreach( $approvalstates as $approvalstate) {
          $attach = [];
          array_push($toReturn, ["ApprovalState"=>$approvalstate, "attach"=>$attach]);
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
         $result = $row['ApprovalState'];
         $exist = ApprovalState::where('id',$result['id'])->first();
         if ($exist) {
           ApprovalState::where('id', $result['id'])->update([
             'value'=>$result['value'],
             'date_assigment'=>$result['date_assigment'],
             'notes'=>$result['notes'],
             'id_user'=>$result['id_user'],
             'date_fullfill'=>$result['date_fullfill'],
             'register_id'=>$result['register_id'],
             'approval_id'=>$result['approval_id'],
           ]);
         } else {
          $approvalstate = new ApprovalState();
          $approvalstate->id = $result['id'];
          $approvalstate->value = $result['value'];
          $approvalstate->date_assigment = $result['date_assigment'];
          $approvalstate->notes = $result['notes'];
          $approvalstate->id_user = $result['id_user'];
          $approvalstate->date_fullfill = $result['date_fullfill'];
          $approvalstate->register_id = $result['register_id'];
          $approvalstate->approval_id = $result['approval_id'];
          $approvalstate->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}