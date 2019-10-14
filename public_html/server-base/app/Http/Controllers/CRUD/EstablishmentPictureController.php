<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\EstablishmentPicture;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EstablishmentPictureController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(EstablishmentPicture::get(),200);
       } else {
          $establishmentpicture = EstablishmentPicture::findOrFail($id);
          $attach = [];
          return response()->json(["EstablishmentPicture"=>$establishmentpicture, "attach"=>$attach],200);
       }
    }

    function getByEstablishmentId(Request $data) {
      $id = $data['id'];
      return response()->json(EstablishmentPicture::where('establishment_id',$id)->first(),200);
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(EstablishmentPicture::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentpicture = new EstablishmentPicture();
          $lastEstablishmentPicture = EstablishmentPicture::orderBy('id')->get()->last();
          if($lastEstablishmentPicture) {
             $establishmentpicture->id = $lastEstablishmentPicture->id + 1;
          } else {
             $establishmentpicture->id = 1;
          }
          $establishmentpicture->establishment_picture_file_type = $result['establishment_picture_file_type'];
          $establishmentpicture->establishment_picture_file_name = $result['establishment_picture_file_name'];
          $establishmentpicture->establishment_picture_file = $result['establishment_picture_file'];
          $establishmentpicture->establishment_id = $establishmentpicture->id;
          $establishmentpicture->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentpicture,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentpicture = EstablishmentPicture::where('id',$result['id'])->update([
             'establishment_picture_file_type'=>$result['establishment_picture_file_type'],
             'establishment_picture_file_name'=>$result['establishment_picture_file_name'],
             'establishment_picture_file'=>$result['establishment_picture_file'],
             'establishment_id'=>$result['establishment_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentpicture,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return EstablishmentPicture::destroy($id);
    }

    function backup(Request $data)
    {
       $establishmentpictures = EstablishmentPicture::get();
       $toReturn = [];
       foreach( $establishmentpictures as $establishmentpicture) {
          $attach = [];
          array_push($toReturn, ["EstablishmentPicture"=>$establishmentpicture, "attach"=>$attach]);
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
         $result = $row['EstablishmentPicture'];
         $exist = EstablishmentPicture::where('id',$result['id'])->first();
         if ($exist) {
           EstablishmentPicture::where('id', $result['id'])->update([
             'establishment_picture_file_type'=>$result['establishment_picture_file_type'],
             'establishment_picture_file_name'=>$result['establishment_picture_file_name'],
             'establishment_picture_file'=>$result['establishment_picture_file'],
             'establishment_id'=>$result['establishment_id'],
           ]);
         } else {
          $establishmentpicture = new EstablishmentPicture();
          $establishmentpicture->id = $result['id'];
          $establishmentpicture->establishment_picture_file_type = $result['establishment_picture_file_type'];
          $establishmentpicture->establishment_picture_file_name = $result['establishment_picture_file_name'];
          $establishmentpicture->establishment_picture_file = $result['establishment_picture_file'];
          $establishmentpicture->establishment_id = $result['establishment_id'];
          $establishmentpicture->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}