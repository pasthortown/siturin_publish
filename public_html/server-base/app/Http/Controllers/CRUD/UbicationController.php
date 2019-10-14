<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Ubication;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UbicationController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Ubication::get(),200);
       } else {
          $ubication = Ubication::findOrFail($id);
          $attach = [];
          return response()->json(["Ubication"=>$ubication, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Ubication::paginate($size),200);
    }

    function get_by_id_lower(Request $data) {
      $id = $data['id'];
      $parroquia = Ubication::where('id', $id)->first();
      $canton = Ubication::where('code', $parroquia->father_code)->first();
      $provincia = Ubication::where('code', $canton->father_code)->first();
      $zonal = Ubication::where('code', $provincia->father_code)->first();
      $region = $zonal->code == 8 ? 2 : 1;
      return response()->json(["region"=>$region, "zonal"=>$zonal, "provincia"=>$provincia, "canton"=>$canton, "parroquia"=>$parroquia],200);
    }

    function filtered(Request $data)
    {
       $filter = $data['filter'];
       if($filter === 'all') {
         return response()->json(Ubication::orderBy('name', 'ASC')->get(),200);
       } else {
         return response()->json(Ubication::where('father_code', $filter)->orderBy('name', 'ASC')->get(),200);
       }
    }
    function filtered_paginate(Request $data)
    {
       $size = $data['size'];
       $filter = $data['filter'];
       if($filter === 'all') {
         return response()->json(Ubication::paginate($size),200);
       } else {
         return response()->json(Ubication::where('father_code', $filter)->paginate($size),200);
       }
    }
    
    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $ubication = new Ubication();
          $lastUbication = Ubication::orderBy('id')->get()->last();
          if($lastUbication) {
             $ubication->id = $lastUbication->id + 1;
          } else {
             $ubication->id = 1;
          }
          $ubication->name = $result['name'];
          $ubication->code = $result['code'];
          $ubication->father_code = $result['father_code'];
          $ubication->acronym = $result['acronym'];
          $ubication->gmap_reference_latitude = $result['gmap_reference_latitude'];
          $ubication->gmap_reference_longitude = $result['gmap_reference_longitude'];
          $ubication->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($ubication,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $ubication = Ubication::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
             'acronym'=>$result['acronym'],
             'gmap_reference_latitude'=>$result['gmap_reference_latitude'],
             'gmap_reference_longitude'=>$result['gmap_reference_longitude'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($ubication,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Ubication::destroy($id);
    }

    function backup(Request $data)
    {
       $ubications = Ubication::get();
       $toReturn = [];
       foreach( $ubications as $ubication) {
          $attach = [];
          array_push($toReturn, ["Ubication"=>$ubication, "attach"=>$attach]);
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
         $result = $row['Ubication'];
         $exist = Ubication::where('id',$result['id'])->first();
         if ($exist) {
           Ubication::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
             'acronym'=>$result['acronym'],
             'gmap_reference_latitude'=>$result['gmap_reference_latitude'],
             'gmap_reference_longitude'=>$result['gmap_reference_longitude'],
           ]);
         } else {
          $ubication = new Ubication();
          $ubication->id = $result['id'];
          $ubication->name = $result['name'];
          $ubication->code = $result['code'];
          $ubication->father_code = $result['father_code'];
          $ubication->acronym = $result['acronym'];
          $ubication->gmap_reference_latitude = $result['gmap_reference_latitude'];
          $ubication->gmap_reference_longitude = $result['gmap_reference_longitude'];
          $ubication->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}