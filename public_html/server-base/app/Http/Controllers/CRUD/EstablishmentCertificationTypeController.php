<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\EstablishmentCertificationType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EstablishmentCertificationTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(EstablishmentCertificationType::get(),200);
       } else {
          $establishmentcertificationtype = EstablishmentCertificationType::findOrFail($id);
          $attach = [];
          return response()->json(["EstablishmentCertificationType"=>$establishmentcertificationtype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(EstablishmentCertificationType::paginate($size),200);
    }

    function filtered(Request $data)
    {
       $filter = $data['filter'];
       if($filter === 'all') {
         return response()->json(EstablishmentCertificationType::get(),200);
       } else {
         return response()->json(EstablishmentCertificationType::where('father_code', $filter)->get(),200);
       }
    }

    function filtered_paginate(Request $data)
    {
       $size = $data['size'];
       $filter = $data['filter'];
       if($filter === 'all') {
         return response()->json(EstablishmentCertificationType::paginate($size),200);
       } else {
         return response()->json(EstablishmentCertificationType::where('father_code', $filter)->paginate($size),200);
       }
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentcertificationtype = new EstablishmentCertificationType();
          $lastEstablishmentCertificationType = EstablishmentCertificationType::orderBy('id')->get()->last();
          if($lastEstablishmentCertificationType) {
             $establishmentcertificationtype->id = $lastEstablishmentCertificationType->id + 1;
          } else {
             $establishmentcertificationtype->id = 1;
          }
          $establishmentcertificationtype->name = $result['name'];
          $establishmentcertificationtype->code = $result['code'];
          $establishmentcertificationtype->father_code = $result['father_code'];
          $establishmentcertificationtype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentcertificationtype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentcertificationtype = EstablishmentCertificationType::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentcertificationtype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return EstablishmentCertificationType::destroy($id);
    }

    function backup(Request $data)
    {
       $establishmentcertificationtypes = EstablishmentCertificationType::get();
       $toReturn = [];
       foreach( $establishmentcertificationtypes as $establishmentcertificationtype) {
          $attach = [];
          array_push($toReturn, ["EstablishmentCertificationType"=>$establishmentcertificationtype, "attach"=>$attach]);
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
         $result = $row['EstablishmentCertificationType'];
         $exist = EstablishmentCertificationType::where('id',$result['id'])->first();
         if ($exist) {
           EstablishmentCertificationType::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
           ]);
         } else {
          $establishmentcertificationtype = new EstablishmentCertificationType();
          $establishmentcertificationtype->id = $result['id'];
          $establishmentcertificationtype->name = $result['name'];
          $establishmentcertificationtype->code = $result['code'];
          $establishmentcertificationtype->father_code = $result['father_code'];
          $establishmentcertificationtype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}