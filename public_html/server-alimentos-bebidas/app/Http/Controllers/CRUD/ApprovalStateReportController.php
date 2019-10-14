<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\ApprovalStateReport;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApprovalStateReportController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(ApprovalStateReport::get(),200);
       } else {
          $approvalstatereport = ApprovalStateReport::findOrFail($id);
          $attach = [];
          return response()->json(["ApprovalStateReport"=>$approvalstatereport, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(ApprovalStateReport::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $approvalstatereport = new ApprovalStateReport();
          $lastApprovalStateReport = ApprovalStateReport::orderBy('id')->get()->last();
          if($lastApprovalStateReport) {
             $approvalstatereport->id = $lastApprovalStateReport->id + 1;
          } else {
             $approvalstatereport->id = 1;
          }
          $approvalstatereport->background = $result['background'];
          $approvalstatereport->actions_done = $result['actions_done'];
          $approvalstatereport->conclution = $result['conclution'];
          $approvalstatereport->recomendation = $result['recomendation'];
          $approvalstatereport->approval_state_id = $result['approval_state_id'];
          $approvalstatereport->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($approvalstatereport,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $approvalstatereport = ApprovalStateReport::where('id',$result['id'])->update([
             'background'=>$result['background'],
             'actions_done'=>$result['actions_done'],
             'conclution'=>$result['conclution'],
             'recomendation'=>$result['recomendation'],
             'approval_state_id'=>$result['approval_state_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($approvalstatereport,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return ApprovalStateReport::destroy($id);
    }

    function backup(Request $data)
    {
       $approvalstatereports = ApprovalStateReport::get();
       $toReturn = [];
       foreach( $approvalstatereports as $approvalstatereport) {
          $attach = [];
          array_push($toReturn, ["ApprovalStateReport"=>$approvalstatereport, "attach"=>$attach]);
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
         $result = $row['ApprovalStateReport'];
         $exist = ApprovalStateReport::where('id',$result['id'])->first();
         if ($exist) {
           ApprovalStateReport::where('id', $result['id'])->update([
             'background'=>$result['background'],
             'actions_done'=>$result['actions_done'],
             'conclution'=>$result['conclution'],
             'recomendation'=>$result['recomendation'],
             'approval_state_id'=>$result['approval_state_id'],
           ]);
         } else {
          $approvalstatereport = new ApprovalStateReport();
          $approvalstatereport->id = $result['id'];
          $approvalstatereport->background = $result['background'];
          $approvalstatereport->actions_done = $result['actions_done'];
          $approvalstatereport->conclution = $result['conclution'];
          $approvalstatereport->recomendation = $result['recomendation'];
          $approvalstatereport->approval_state_id = $result['approval_state_id'];
          $approvalstatereport->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}