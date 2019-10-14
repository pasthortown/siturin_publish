<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\PropertyTitleAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PropertyTitleAttachmentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(PropertyTitleAttachment::get(),200);
       } else {
          $propertytitleattachment = PropertyTitleAttachment::findOrFail($id);
          $attach = [];
          return response()->json(["PropertyTitleAttachment"=>$propertytitleattachment, "attach"=>$attach],200);
       }
    }

    function get_by_register_id(Request $data) {
      $register_id = $data['register_id'];
      return response()->json(PropertyTitleAttachment::where('register_id', $register_id)->first(),200);
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(PropertyTitleAttachment::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $propertytitleattachment = new PropertyTitleAttachment();
          $lastPropertyTitleAttachment = PropertyTitleAttachment::orderBy('id')->get()->last();
          if($lastPropertyTitleAttachment) {
             $propertytitleattachment->id = $lastPropertyTitleAttachment->id + 1;
          } else {
             $propertytitleattachment->id = 1;
          }
          $propertytitleattachment->property_title_attachment_file_type = $result['property_title_attachment_file_type'];
          $propertytitleattachment->property_title_attachment_file_name = $result['property_title_attachment_file_name'];
          $propertytitleattachment->property_title_attachment_file = $result['property_title_attachment_file'];
          $propertytitleattachment->register_id = $result['register_id'];
          $propertytitleattachment->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($propertytitleattachment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $propertytitleattachment = PropertyTitleAttachment::where('id',$result['id'])->update([
             'property_title_attachment_file_type'=>$result['property_title_attachment_file_type'],
             'property_title_attachment_file_name'=>$result['property_title_attachment_file_name'],
             'property_title_attachment_file'=>$result['property_title_attachment_file'],
             'register_id'=>$result['register_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($propertytitleattachment,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return PropertyTitleAttachment::destroy($id);
    }

    function backup(Request $data)
    {
       $propertytitleattachments = PropertyTitleAttachment::get();
       $toReturn = [];
       foreach( $propertytitleattachments as $propertytitleattachment) {
          $attach = [];
          array_push($toReturn, ["PropertyTitleAttachment"=>$propertytitleattachment, "attach"=>$attach]);
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
         $result = $row['PropertyTitleAttachment'];
         $exist = PropertyTitleAttachment::where('id',$result['id'])->first();
         if ($exist) {
           PropertyTitleAttachment::where('id', $result['id'])->update([
             'property_title_attachment_file_type'=>$result['property_title_attachment_file_type'],
             'property_title_attachment_file_name'=>$result['property_title_attachment_file_name'],
             'property_title_attachment_file'=>$result['property_title_attachment_file'],
             'register_id'=>$result['register_id'],
           ]);
         } else {
          $propertytitleattachment = new PropertyTitleAttachment();
          $propertytitleattachment->id = $result['id'];
          $propertytitleattachment->property_title_attachment_file_type = $result['property_title_attachment_file_type'];
          $propertytitleattachment->property_title_attachment_file_name = $result['property_title_attachment_file_name'];
          $propertytitleattachment->property_title_attachment_file = $result['property_title_attachment_file'];
          $propertytitleattachment->register_id = $result['register_id'];
          $propertytitleattachment->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}