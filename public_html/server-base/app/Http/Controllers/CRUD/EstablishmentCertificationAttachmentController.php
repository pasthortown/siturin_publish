<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\EstablishmentCertificationAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EstablishmentCertificationAttachmentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(EstablishmentCertificationAttachment::get(),200);
       } else {
          $establishmentcertificationattachment = EstablishmentCertificationAttachment::findOrFail($id);
          $attach = [];
          return response()->json(["EstablishmentCertificationAttachment"=>$establishmentcertificationattachment, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(EstablishmentCertificationAttachment::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentcertificationattachment = new EstablishmentCertificationAttachment();
          $lastEstablishmentCertificationAttachment = EstablishmentCertificationAttachment::orderBy('id')->get()->last();
          if($lastEstablishmentCertificationAttachment) {
             $establishmentcertificationattachment->id = $lastEstablishmentCertificationAttachment->id + 1;
          } else {
             $establishmentcertificationattachment->id = 1;
          }
          $establishmentcertificationattachment->establishment_certification_attachment_file_type = $result['establishment_certification_attachment_file_type'];
          $establishmentcertificationattachment->establishment_certification_attachment_file_name = $result['establishment_certification_attachment_file_name'];
          $establishmentcertificationattachment->establishment_certification_attachment_file = $result['establishment_certification_attachment_file'];
          $establishmentcertificationattachment->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentcertificationattachment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentcertificationattachment = EstablishmentCertificationAttachment::where('id',$result['id'])->update([
             'establishment_certification_attachment_file_type'=>$result['establishment_certification_attachment_file_type'],
             'establishment_certification_attachment_file_name'=>$result['establishment_certification_attachment_file_name'],
             'establishment_certification_attachment_file'=>$result['establishment_certification_attachment_file'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentcertificationattachment,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return EstablishmentCertificationAttachment::destroy($id);
    }

    function backup(Request $data)
    {
       $establishmentcertificationattachments = EstablishmentCertificationAttachment::get();
       $toReturn = [];
       foreach( $establishmentcertificationattachments as $establishmentcertificationattachment) {
          $attach = [];
          array_push($toReturn, ["EstablishmentCertificationAttachment"=>$establishmentcertificationattachment, "attach"=>$attach]);
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
         $result = $row['EstablishmentCertificationAttachment'];
         $exist = EstablishmentCertificationAttachment::where('id',$result['id'])->first();
         if ($exist) {
           EstablishmentCertificationAttachment::where('id', $result['id'])->update([
             'establishment_certification_attachment_file_type'=>$result['establishment_certification_attachment_file_type'],
             'establishment_certification_attachment_file_name'=>$result['establishment_certification_attachment_file_name'],
             'establishment_certification_attachment_file'=>$result['establishment_certification_attachment_file'],
           ]);
         } else {
          $establishmentcertificationattachment = new EstablishmentCertificationAttachment();
          $establishmentcertificationattachment->id = $result['id'];
          $establishmentcertificationattachment->establishment_certification_attachment_file_type = $result['establishment_certification_attachment_file_type'];
          $establishmentcertificationattachment->establishment_certification_attachment_file_name = $result['establishment_certification_attachment_file_name'];
          $establishmentcertificationattachment->establishment_certification_attachment_file = $result['establishment_certification_attachment_file'];
          $establishmentcertificationattachment->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}