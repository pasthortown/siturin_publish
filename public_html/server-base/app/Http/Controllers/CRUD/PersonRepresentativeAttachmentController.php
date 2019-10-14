<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\PersonRepresentativeAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PersonRepresentativeAttachmentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(PersonRepresentativeAttachment::get(),200);
       } else {
          $personrepresentativeattachment = PersonRepresentativeAttachment::findOrFail($id);
          $attach = [];
          return response()->json(["PersonRepresentativeAttachment"=>$personrepresentativeattachment, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(PersonRepresentativeAttachment::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $personrepresentativeattachment = new PersonRepresentativeAttachment();
          $lastPersonRepresentativeAttachment = PersonRepresentativeAttachment::orderBy('id')->get()->last();
          if($lastPersonRepresentativeAttachment) {
             $personrepresentativeattachment->id = $lastPersonRepresentativeAttachment->id + 1;
          } else {
             $personrepresentativeattachment->id = 1;
          }
          $personrepresentativeattachment->person_representative_attachment_file_type = $result['person_representative_attachment_file_type'];
          $personrepresentativeattachment->person_representative_attachment_file_name = $result['person_representative_attachment_file_name'];
          $personrepresentativeattachment->person_representative_attachment_file = $result['person_representative_attachment_file'];
          $personrepresentativeattachment->ruc = $result['ruc'];
          $personrepresentativeattachment->assignment_date = $result['assignment_date'];
          $personrepresentativeattachment->person_representative_id = $personrepresentativeattachment->id;
          $personrepresentativeattachment->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($personrepresentativeattachment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $personrepresentativeattachment = PersonRepresentativeAttachment::where('id',$result['id'])->update([
             'person_representative_attachment_file_type'=>$result['person_representative_attachment_file_type'],
             'person_representative_attachment_file_name'=>$result['person_representative_attachment_file_name'],
             'person_representative_attachment_file'=>$result['person_representative_attachment_file'],
             'ruc'=>$result['ruc'],
             'assignment_date'=>$result['assignment_date'],
             'person_representative_id'=>$result['person_representative_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($personrepresentativeattachment,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return PersonRepresentativeAttachment::destroy($id);
    }

    function filtered(Request $data) {
      $rucNumber = $data['ruc_number'];
      $personRepresentativeAttachment = PersonRepresentativeAttachment::where('ruc', $rucNumber)->first();
      if ($personRepresentativeAttachment) {
         return $personRepresentativeAttachment;
      } else {
         return 0;
      }
    }

    function backup(Request $data)
    {
       $personrepresentativeattachments = PersonRepresentativeAttachment::get();
       $toReturn = [];
       foreach( $personrepresentativeattachments as $personrepresentativeattachment) {
          $attach = [];
          array_push($toReturn, ["PersonRepresentativeAttachment"=>$personrepresentativeattachment, "attach"=>$attach]);
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
         $result = $row['PersonRepresentativeAttachment'];
         $exist = PersonRepresentativeAttachment::where('id',$result['id'])->first();
         if ($exist) {
           PersonRepresentativeAttachment::where('id', $result['id'])->update([
             'person_representative_attachment_file_type'=>$result['person_representative_attachment_file_type'],
             'person_representative_attachment_file_name'=>$result['person_representative_attachment_file_name'],
             'person_representative_attachment_file'=>$result['person_representative_attachment_file'],
             'ruc'=>$result['ruc'],
             'assignment_date'=>$result['assignment_date'],
             'person_representative_id'=>$result['person_representative_id'],
           ]);
         } else {
          $personrepresentativeattachment = new PersonRepresentativeAttachment();
          $personrepresentativeattachment->id = $result['id'];
          $personrepresentativeattachment->person_representative_attachment_file_type = $result['person_representative_attachment_file_type'];
          $personrepresentativeattachment->person_representative_attachment_file_name = $result['person_representative_attachment_file_name'];
          $personrepresentativeattachment->person_representative_attachment_file = $result['person_representative_attachment_file'];
          $personrepresentativeattachment->ruc = $result['ruc'];
          $personrepresentativeattachment->assignment_date = $result['assignment_date'];
          $personrepresentativeattachment->person_representative_id = $result['person_representative_id'];
          $personrepresentativeattachment->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}