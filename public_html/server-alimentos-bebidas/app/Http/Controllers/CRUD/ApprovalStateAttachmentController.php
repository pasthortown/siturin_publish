<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\ApprovalStateAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApprovalStateAttachmentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(ApprovalStateAttachment::get(),200);
       } else {
          $approvalstateattachment = ApprovalStateAttachment::findOrFail($id);
          $attach = [];
          return response()->json(["ApprovalStateAttachment"=>$approvalstateattachment, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(ApprovalStateAttachment::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $approvalstateattachment = new ApprovalStateAttachment();
          $lastApprovalStateAttachment = ApprovalStateAttachment::orderBy('id')->get()->last();
          if($lastApprovalStateAttachment) {
             $approvalstateattachment->id = $lastApprovalStateAttachment->id + 1;
          } else {
             $approvalstateattachment->id = 1;
          }
          $approvalstateattachment->approval_state_attachment_file_type = $result['approval_state_attachment_file_type'];
          $approvalstateattachment->approval_state_attachment_file_name = $result['approval_state_attachment_file_name'];
          $approvalstateattachment->approval_state_attachment_file = $result['approval_state_attachment_file'];
          $approvalstateattachment->approval_state_id = $result['approval_state_id'];
          $approvalstateattachment->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($approvalstateattachment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $approvalstateattachment = ApprovalStateAttachment::where('id',$result['id'])->update([
             'approval_state_attachment_file_type'=>$result['approval_state_attachment_file_type'],
             'approval_state_attachment_file_name'=>$result['approval_state_attachment_file_name'],
             'approval_state_attachment_file'=>$result['approval_state_attachment_file'],
             'approval_state_id'=>$result['approval_state_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($approvalstateattachment,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return ApprovalStateAttachment::destroy($id);
    }

    function backup(Request $data)
    {
       $approvalstateattachments = ApprovalStateAttachment::get();
       $toReturn = [];
       foreach( $approvalstateattachments as $approvalstateattachment) {
          $attach = [];
          array_push($toReturn, ["ApprovalStateAttachment"=>$approvalstateattachment, "attach"=>$attach]);
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
         $result = $row['ApprovalStateAttachment'];
         $exist = ApprovalStateAttachment::where('id',$result['id'])->first();
         if ($exist) {
           ApprovalStateAttachment::where('id', $result['id'])->update([
             'approval_state_attachment_file_type'=>$result['approval_state_attachment_file_type'],
             'approval_state_attachment_file_name'=>$result['approval_state_attachment_file_name'],
             'approval_state_attachment_file'=>$result['approval_state_attachment_file'],
             'approval_state_id'=>$result['approval_state_id'],
           ]);
         } else {
          $approvalstateattachment = new ApprovalStateAttachment();
          $approvalstateattachment->id = $result['id'];
          $approvalstateattachment->approval_state_attachment_file_type = $result['approval_state_attachment_file_type'];
          $approvalstateattachment->approval_state_attachment_file_name = $result['approval_state_attachment_file_name'];
          $approvalstateattachment->approval_state_attachment_file = $result['approval_state_attachment_file'];
          $approvalstateattachment->approval_state_id = $result['approval_state_id'];
          $approvalstateattachment->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}