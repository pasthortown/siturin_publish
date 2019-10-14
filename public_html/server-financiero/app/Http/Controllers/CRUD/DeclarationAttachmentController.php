<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\DeclarationAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeclarationAttachmentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(DeclarationAttachment::get(),200);
       } else {
          $declarationattachment = DeclarationAttachment::findOrFail($id);
          $attach = [];
          return response()->json(["DeclarationAttachment"=>$declarationattachment, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(DeclarationAttachment::paginate($size),200);
    }

    function get_by_declaration_id(Request $data) {
      $declaration_id = $data['id'];
      return response()->json(DeclarationAttachment::where('declaration_id', $declaration_id)->first(),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $declarationattachment = new DeclarationAttachment();
          $lastDeclarationAttachment = DeclarationAttachment::orderBy('id')->get()->last();
          if($lastDeclarationAttachment) {
             $declarationattachment->id = $lastDeclarationAttachment->id + 1;
          } else {
             $declarationattachment->id = 1;
          }
          $declarationattachment->declaration_attachment_file_type = $result['declaration_attachment_file_type'];
          $declarationattachment->declaration_attachment_file_name = $result['declaration_attachment_file_name'];
          $declarationattachment->declaration_attachment_file = $result['declaration_attachment_file'];
          $declarationattachment->declaration_id = $result['declaration_id'];
          $declarationattachment->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($declarationattachment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $declarationattachment = DeclarationAttachment::where('id',$result['id'])->update([
             'declaration_attachment_file_type'=>$result['declaration_attachment_file_type'],
             'declaration_attachment_file_name'=>$result['declaration_attachment_file_name'],
             'declaration_attachment_file'=>$result['declaration_attachment_file'],
             'declaration_id'=>$result['declaration_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($declarationattachment,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return DeclarationAttachment::destroy($id);
    }

    function backup(Request $data)
    {
       $declarationattachments = DeclarationAttachment::get();
       $toReturn = [];
       foreach( $declarationattachments as $declarationattachment) {
          $attach = [];
          array_push($toReturn, ["DeclarationAttachment"=>$declarationattachment, "attach"=>$attach]);
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
         $result = $row['DeclarationAttachment'];
         $exist = DeclarationAttachment::where('id',$result['id'])->first();
         if ($exist) {
           DeclarationAttachment::where('id', $result['id'])->update([
             'declaration_attachment_file_type'=>$result['declaration_attachment_file_type'],
             'declaration_attachment_file_name'=>$result['declaration_attachment_file_name'],
             'declaration_attachment_file'=>$result['declaration_attachment_file'],
             'declaration_id'=>$result['declaration_id'],
           ]);
         } else {
          $declarationattachment = new DeclarationAttachment();
          $declarationattachment->id = $result['id'];
          $declarationattachment->declaration_attachment_file_type = $result['declaration_attachment_file_type'];
          $declarationattachment->declaration_attachment_file_name = $result['declaration_attachment_file_name'];
          $declarationattachment->declaration_attachment_file = $result['declaration_attachment_file'];
          $declarationattachment->declaration_id = $result['declaration_id'];
          $declarationattachment->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}