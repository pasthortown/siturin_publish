<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\FloorAuthorizationCertificate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FloorAuthorizationCertificateController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(FloorAuthorizationCertificate::get(),200);
       } else {
          $floorauthorizationcertificate = FloorAuthorizationCertificate::findOrFail($id);
          $attach = [];
          return response()->json(["FloorAuthorizationCertificate"=>$floorauthorizationcertificate, "attach"=>$attach],200);
       }
    }

    function get_by_register_id(Request $data) {
      $register_id = $data['register_id'];
      return response()->json(FloorAuthorizationCertificate::where('register_id', $register_id)->first(),200);
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(FloorAuthorizationCertificate::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $floorauthorizationcertificate = new FloorAuthorizationCertificate();
          $lastFloorAuthorizationCertificate = FloorAuthorizationCertificate::orderBy('id')->get()->last();
          if($lastFloorAuthorizationCertificate) {
             $floorauthorizationcertificate->id = $lastFloorAuthorizationCertificate->id + 1;
          } else {
             $floorauthorizationcertificate->id = 1;
          }
          $floorauthorizationcertificate->floor_authorization_certificate_file_type = $result['floor_authorization_certificate_file_type'];
          $floorauthorizationcertificate->floor_authorization_certificate_file_name = $result['floor_authorization_certificate_file_name'];
          $floorauthorizationcertificate->floor_authorization_certificate_file = $result['floor_authorization_certificate_file'];
          $floorauthorizationcertificate->register_id = $result['register_id'];
          $floorauthorizationcertificate->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($floorauthorizationcertificate,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $floorauthorizationcertificate = FloorAuthorizationCertificate::where('id',$result['id'])->update([
             'floor_authorization_certificate_file_type'=>$result['floor_authorization_certificate_file_type'],
             'floor_authorization_certificate_file_name'=>$result['floor_authorization_certificate_file_name'],
             'floor_authorization_certificate_file'=>$result['floor_authorization_certificate_file'],
             'register_id'=>$result['register_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($floorauthorizationcertificate,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return FloorAuthorizationCertificate::destroy($id);
    }

    function backup(Request $data)
    {
       $floorauthorizationcertificates = FloorAuthorizationCertificate::get();
       $toReturn = [];
       foreach( $floorauthorizationcertificates as $floorauthorizationcertificate) {
          $attach = [];
          array_push($toReturn, ["FloorAuthorizationCertificate"=>$floorauthorizationcertificate, "attach"=>$attach]);
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
         $result = $row['FloorAuthorizationCertificate'];
         $exist = FloorAuthorizationCertificate::where('id',$result['id'])->first();
         if ($exist) {
           FloorAuthorizationCertificate::where('id', $result['id'])->update([
             'floor_authorization_certificate_file_type'=>$result['floor_authorization_certificate_file_type'],
             'floor_authorization_certificate_file_name'=>$result['floor_authorization_certificate_file_name'],
             'floor_authorization_certificate_file'=>$result['floor_authorization_certificate_file'],
             'register_id'=>$result['register_id'],
           ]);
         } else {
          $floorauthorizationcertificate = new FloorAuthorizationCertificate();
          $floorauthorizationcertificate->id = $result['id'];
          $floorauthorizationcertificate->floor_authorization_certificate_file_type = $result['floor_authorization_certificate_file_type'];
          $floorauthorizationcertificate->floor_authorization_certificate_file_name = $result['floor_authorization_certificate_file_name'];
          $floorauthorizationcertificate->floor_authorization_certificate_file = $result['floor_authorization_certificate_file'];
          $floorauthorizationcertificate->register_id = $result['register_id'];
          $floorauthorizationcertificate->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}