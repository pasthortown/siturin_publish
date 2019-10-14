<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\EstablishmentCertification;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EstablishmentCertificationController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(EstablishmentCertification::get(),200);
       } else {
          $establishmentcertification = EstablishmentCertification::findOrFail($id);
          $attach = [];
          return response()->json(["EstablishmentCertification"=>$establishmentcertification, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(EstablishmentCertification::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentcertification = new EstablishmentCertification();
          $lastEstablishmentCertification = EstablishmentCertification::orderBy('id')->get()->last();
          if($lastEstablishmentCertification) {
             $establishmentcertification->id = $lastEstablishmentCertification->id + 1;
          } else {
             $establishmentcertification->id = 1;
          }
          $establishmentcertification->establishment_certification_type_id = $result['establishment_certification_type_id'];
          $establishmentcertification->establishment_certification_attachment_id = $result['establishment_certification_attachment_id'];
          $establishmentcertification->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentcertification,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentcertification = EstablishmentCertification::where('id',$result['id'])->update([
             'establishment_certification_type_id'=>$result['establishment_certification_type_id'],
             'establishment_certification_attachment_id'=>$result['establishment_certification_attachment_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentcertification,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return EstablishmentCertification::destroy($id);
    }

    function backup(Request $data)
    {
       $establishmentcertifications = EstablishmentCertification::get();
       $toReturn = [];
       foreach( $establishmentcertifications as $establishmentcertification) {
          $attach = [];
          array_push($toReturn, ["EstablishmentCertification"=>$establishmentcertification, "attach"=>$attach]);
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
         $result = $row['EstablishmentCertification'];
         $exist = EstablishmentCertification::where('id',$result['id'])->first();
         if ($exist) {
           EstablishmentCertification::where('id', $result['id'])->update([
             'establishment_certification_type_id'=>$result['establishment_certification_type_id'],
             'establishment_certification_attachment_id'=>$result['establishment_certification_attachment_id'],
           ]);
         } else {
          $establishmentcertification = new EstablishmentCertification();
          $establishmentcertification->id = $result['id'];
          $establishmentcertification->establishment_certification_type_id = $result['establishment_certification_type_id'];
          $establishmentcertification->establishment_certification_attachment_id = $result['establishment_certification_attachment_id'];
          $establishmentcertification->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}