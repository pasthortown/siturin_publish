<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\PayAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PayAttachmentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(PayAttachment::get(),200);
       } else {
          $payattachment = PayAttachment::findOrFail($id);
          $attach = [];
          return response()->json(["PayAttachment"=>$payattachment, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(PayAttachment::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $payattachment = new PayAttachment();
          $lastPayAttachment = PayAttachment::orderBy('id')->get()->last();
          if($lastPayAttachment) {
             $payattachment->id = $lastPayAttachment->id + 1;
          } else {
             $payattachment->id = 1;
          }
          $payattachment->pay_attachment_file_type = $result['pay_attachment_file_type'];
          $payattachment->pay_attachment_file_name = $result['pay_attachment_file_name'];
          $payattachment->pay_attachment_file = $result['pay_attachment_file'];
          $payattachment->pay_id = $result['pay_id'];
          $payattachment->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($payattachment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $payattachment = PayAttachment::where('id',$result['id'])->update([
             'pay_attachment_file_type'=>$result['pay_attachment_file_type'],
             'pay_attachment_file_name'=>$result['pay_attachment_file_name'],
             'pay_attachment_file'=>$result['pay_attachment_file'],
             'pay_id'=>$result['pay_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($payattachment,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return PayAttachment::destroy($id);
    }

    function backup(Request $data)
    {
       $payattachments = PayAttachment::get();
       $toReturn = [];
       foreach( $payattachments as $payattachment) {
          $attach = [];
          array_push($toReturn, ["PayAttachment"=>$payattachment, "attach"=>$attach]);
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
         $result = $row['PayAttachment'];
         $exist = PayAttachment::where('id',$result['id'])->first();
         if ($exist) {
           PayAttachment::where('id', $result['id'])->update([
             'pay_attachment_file_type'=>$result['pay_attachment_file_type'],
             'pay_attachment_file_name'=>$result['pay_attachment_file_name'],
             'pay_attachment_file'=>$result['pay_attachment_file'],
             'pay_id'=>$result['pay_id'],
           ]);
         } else {
          $payattachment = new PayAttachment();
          $payattachment->id = $result['id'];
          $payattachment->pay_attachment_file_type = $result['pay_attachment_file_type'];
          $payattachment->pay_attachment_file_name = $result['pay_attachment_file_name'];
          $payattachment->pay_attachment_file = $result['pay_attachment_file'];
          $payattachment->pay_id = $result['pay_id'];
          $payattachment->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}