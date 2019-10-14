<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\PreviewRegisterCode;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PreviewRegisterCodeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(PreviewRegisterCode::get(),200);
       } else {
          $previewregistercode = PreviewRegisterCode::findOrFail($id);
          $attach = [];
          return response()->json(["PreviewRegisterCode"=>$previewregistercode, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(PreviewRegisterCode::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $previewregistercode = new PreviewRegisterCode();
          $lastPreviewRegisterCode = PreviewRegisterCode::orderBy('id')->get()->last();
          if($lastPreviewRegisterCode) {
             $previewregistercode->id = $lastPreviewRegisterCode->id + 1;
          } else {
             $previewregistercode->id = 1;
          }
          $previewregistercode->code = $result['code'];
          $previewregistercode->system_name_id = $result['system_name_id'];
          $previewregistercode->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($previewregistercode,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $previewregistercode = PreviewRegisterCode::where('id',$result['id'])->update([
             'code'=>$result['code'],
             'system_name_id'=>$result['system_name_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($previewregistercode,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return PreviewRegisterCode::destroy($id);
    }

    function backup(Request $data)
    {
       $previewregistercodes = PreviewRegisterCode::get();
       $toReturn = [];
       foreach( $previewregistercodes as $previewregistercode) {
          $attach = [];
          array_push($toReturn, ["PreviewRegisterCode"=>$previewregistercode, "attach"=>$attach]);
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
         $result = $row['PreviewRegisterCode'];
         $exist = PreviewRegisterCode::where('id',$result['id'])->first();
         if ($exist) {
           PreviewRegisterCode::where('id', $result['id'])->update([
             'code'=>$result['code'],
             'system_name_id'=>$result['system_name_id'],
           ]);
         } else {
          $previewregistercode = new PreviewRegisterCode();
          $previewregistercode->id = $result['id'];
          $previewregistercode->code = $result['code'];
          $previewregistercode->system_name_id = $result['system_name_id'];
          $previewregistercode->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}