<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\AuthorizationAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorizationAttachmentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(AuthorizationAttachment::get(),200);
       } else {
          $authorizationattachment = AuthorizationAttachment::findOrFail($id);
          $attach = [];
          return response()->json(["AuthorizationAttachment"=>$authorizationattachment, "attach"=>$attach],200);
       }
    }

    function get_by_register_id(Request $data) {
      $register_id = $data['register_id'];
      return response()->json(AuthorizationAttachment::where('register_id', $register_id)->first(),200);
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(AuthorizationAttachment::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $authorizationattachment = new AuthorizationAttachment();
          $lastAuthorizationAttachment = AuthorizationAttachment::orderBy('id')->get()->last();
          if($lastAuthorizationAttachment) {
             $authorizationattachment->id = $lastAuthorizationAttachment->id + 1;
          } else {
             $authorizationattachment->id = 1;
          }
          $authorizationattachment->authorization_attachment_file_type = $result['authorization_attachment_file_type'];
          $authorizationattachment->authorization_attachment_file_name = $result['authorization_attachment_file_name'];
          $authorizationattachment->authorization_attachment_file = $result['authorization_attachment_file'];
          $authorizationattachment->register_id = $result['register_id'];
          $authorizationattachment->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($authorizationattachment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $authorizationattachment = AuthorizationAttachment::where('id',$result['id'])->update([
             'authorization_attachment_file_type'=>$result['authorization_attachment_file_type'],
             'authorization_attachment_file_name'=>$result['authorization_attachment_file_name'],
             'authorization_attachment_file'=>$result['authorization_attachment_file'],
             'register_id'=>$result['register_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($authorizationattachment,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return AuthorizationAttachment::destroy($id);
    }

    function backup(Request $data)
    {
       $authorizationattachments = AuthorizationAttachment::get();
       $toReturn = [];
       foreach( $authorizationattachments as $authorizationattachment) {
          $attach = [];
          array_push($toReturn, ["AuthorizationAttachment"=>$authorizationattachment, "attach"=>$attach]);
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
         $result = $row['AuthorizationAttachment'];
         $exist = AuthorizationAttachment::where('id',$result['id'])->first();
         if ($exist) {
           AuthorizationAttachment::where('id', $result['id'])->update([
             'authorization_attachment_file_type'=>$result['authorization_attachment_file_type'],
             'authorization_attachment_file_name'=>$result['authorization_attachment_file_name'],
             'authorization_attachment_file'=>$result['authorization_attachment_file'],
             'register_id'=>$result['register_id'],
           ]);
         } else {
          $authorizationattachment = new AuthorizationAttachment();
          $authorizationattachment->id = $result['id'];
          $authorizationattachment->authorization_attachment_file_type = $result['authorization_attachment_file_type'];
          $authorizationattachment->authorization_attachment_file_name = $result['authorization_attachment_file_name'];
          $authorizationattachment->authorization_attachment_file = $result['authorization_attachment_file'];
          $authorizationattachment->register_id = $result['register_id'];
          $authorizationattachment->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}