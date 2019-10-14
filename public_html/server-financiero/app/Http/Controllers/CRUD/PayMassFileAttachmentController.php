<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\PayMassFileAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PayMassFileAttachmentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(PayMassFileAttachment::get(),200);
       } else {
          $paymassfileattachment = PayMassFileAttachment::findOrFail($id);
          $attach = [];
          return response()->json(["PayMassFileAttachment"=>$paymassfileattachment, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(PayMassFileAttachment::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $paymassfileattachment = new PayMassFileAttachment();
          $lastPayMassFileAttachment = PayMassFileAttachment::orderBy('id')->get()->last();
          if($lastPayMassFileAttachment) {
             $paymassfileattachment->id = $lastPayMassFileAttachment->id + 1;
          } else {
             $paymassfileattachment->id = 1;
          }
          $paymassfileattachment->pay_mass_file_attachment_file_type = $result['pay_mass_file_attachment_file_type'];
          $paymassfileattachment->pay_mass_file_attachment_file_name = $result['pay_mass_file_attachment_file_name'];
          $paymassfileattachment->pay_mass_file_attachment_file = $result['pay_mass_file_attachment_file'];
          $paymassfileattachment->date = $result['date'];
          $paymassfileattachment->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($paymassfileattachment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $paymassfileattachment = PayMassFileAttachment::where('id',$result['id'])->update([
             'pay_mass_file_attachment_file_type'=>$result['pay_mass_file_attachment_file_type'],
             'pay_mass_file_attachment_file_name'=>$result['pay_mass_file_attachment_file_name'],
             'pay_mass_file_attachment_file'=>$result['pay_mass_file_attachment_file'],
             'date'=>$result['date'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($paymassfileattachment,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return PayMassFileAttachment::destroy($id);
    }

    function backup(Request $data)
    {
       $paymassfileattachments = PayMassFileAttachment::get();
       $toReturn = [];
       foreach( $paymassfileattachments as $paymassfileattachment) {
          $attach = [];
          array_push($toReturn, ["PayMassFileAttachment"=>$paymassfileattachment, "attach"=>$attach]);
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
         $result = $row['PayMassFileAttachment'];
         $exist = PayMassFileAttachment::where('id',$result['id'])->first();
         if ($exist) {
           PayMassFileAttachment::where('id', $result['id'])->update([
             'pay_mass_file_attachment_file_type'=>$result['pay_mass_file_attachment_file_type'],
             'pay_mass_file_attachment_file_name'=>$result['pay_mass_file_attachment_file_name'],
             'pay_mass_file_attachment_file'=>$result['pay_mass_file_attachment_file'],
             'date'=>$result['date'],
           ]);
         } else {
          $paymassfileattachment = new PayMassFileAttachment();
          $paymassfileattachment->id = $result['id'];
          $paymassfileattachment->pay_mass_file_attachment_file_type = $result['pay_mass_file_attachment_file_type'];
          $paymassfileattachment->pay_mass_file_attachment_file_name = $result['pay_mass_file_attachment_file_name'];
          $paymassfileattachment->pay_mass_file_attachment_file = $result['pay_mass_file_attachment_file'];
          $paymassfileattachment->date = $result['date'];
          $paymassfileattachment->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}