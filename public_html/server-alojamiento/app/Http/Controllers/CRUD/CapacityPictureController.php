<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\CapacityPicture;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CapacityPictureController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(CapacityPicture::get(),200);
       } else {
          $capacitypicture = CapacityPicture::findOrFail($id);
          $attach = [];
          return response()->json(["CapacityPicture"=>$capacitypicture, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(CapacityPicture::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $capacitypicture = new CapacityPicture();
          $lastCapacityPicture = CapacityPicture::orderBy('id')->get()->last();
          if($lastCapacityPicture) {
             $capacitypicture->id = $lastCapacityPicture->id + 1;
          } else {
             $capacitypicture->id = 1;
          }
          $capacitypicture->capacity_picture_file_type = $result['capacity_picture_file_type'];
          $capacitypicture->capacity_picture_file_name = $result['capacity_picture_file_name'];
          $capacitypicture->capacity_picture_file = $result['capacity_picture_file'];
          $capacitypicture->capacity_id = $result['capacity_id'];
          $capacitypicture->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($capacitypicture,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $capacitypicture = CapacityPicture::where('id',$result['id'])->update([
             'capacity_picture_file_type'=>$result['capacity_picture_file_type'],
             'capacity_picture_file_name'=>$result['capacity_picture_file_name'],
             'capacity_picture_file'=>$result['capacity_picture_file'],
             'capacity_id'=>$result['capacity_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($capacitypicture,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return CapacityPicture::destroy($id);
    }

    function backup(Request $data)
    {
       $capacitypictures = CapacityPicture::get();
       $toReturn = [];
       foreach( $capacitypictures as $capacitypicture) {
          $attach = [];
          array_push($toReturn, ["CapacityPicture"=>$capacitypicture, "attach"=>$attach]);
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
         $result = $row['CapacityPicture'];
         $exist = CapacityPicture::where('id',$result['id'])->first();
         if ($exist) {
           CapacityPicture::where('id', $result['id'])->update([
             'capacity_picture_file_type'=>$result['capacity_picture_file_type'],
             'capacity_picture_file_name'=>$result['capacity_picture_file_name'],
             'capacity_picture_file'=>$result['capacity_picture_file'],
             'capacity_id'=>$result['capacity_id'],
           ]);
         } else {
          $capacitypicture = new CapacityPicture();
          $capacitypicture->id = $result['id'];
          $capacitypicture->capacity_picture_file_type = $result['capacity_picture_file_type'];
          $capacitypicture->capacity_picture_file_name = $result['capacity_picture_file_name'];
          $capacitypicture->capacity_picture_file = $result['capacity_picture_file'];
          $capacitypicture->capacity_id = $result['capacity_id'];
          $capacitypicture->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}