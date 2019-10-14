<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\ComplementaryServiceType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ComplementaryServiceTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(ComplementaryServiceType::get(),200);
       } else {
          $complementaryservicetype = ComplementaryServiceType::findOrFail($id);
          $attach = [];
          return response()->json(["ComplementaryServiceType"=>$complementaryservicetype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(ComplementaryServiceType::paginate($size),200);
    }

    function filtered(Request $data)
    {
       $filter = $data['filter'];
       if($filter === 'all') {
         return response()->json(ComplementaryServiceType::get(),200);
       } else {
         return response()->json(ComplementaryServiceType::where('father_code', $filter)->get(),200);
       }
    }

    function filtered_paginate(Request $data)
    {
       $size = $data['size'];
       $filter = $data['filter'];
       if($filter === 'all') {
         return response()->json(ComplementaryServiceType::paginate($size),200);
       } else {
         return response()->json(ComplementaryServiceType::where('father_code', $filter)->paginate($size),200);
       }
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $complementaryservicetype = new ComplementaryServiceType();
          $lastComplementaryServiceType = ComplementaryServiceType::orderBy('id')->get()->last();
          if($lastComplementaryServiceType) {
             $complementaryservicetype->id = $lastComplementaryServiceType->id + 1;
          } else {
             $complementaryservicetype->id = 1;
          }
          $complementaryservicetype->name = $result['name'];
          $complementaryservicetype->code = $result['code'];
          $complementaryservicetype->father_code = $result['father_code'];
          $complementaryservicetype->description = $result['description'];
          $complementaryservicetype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($complementaryservicetype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $complementaryservicetype = ComplementaryServiceType::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
             'description'=>$result['description'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($complementaryservicetype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return ComplementaryServiceType::destroy($id);
    }

    function backup(Request $data)
    {
       $complementaryservicetypes = ComplementaryServiceType::get();
       $toReturn = [];
       foreach( $complementaryservicetypes as $complementaryservicetype) {
          $attach = [];
          array_push($toReturn, ["ComplementaryServiceType"=>$complementaryservicetype, "attach"=>$attach]);
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
         $result = $row['ComplementaryServiceType'];
         $exist = ComplementaryServiceType::where('id',$result['id'])->first();
         if ($exist) {
           ComplementaryServiceType::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
             'description'=>$result['description'],
           ]);
         } else {
          $complementaryservicetype = new ComplementaryServiceType();
          $complementaryservicetype->id = $result['id'];
          $complementaryservicetype->name = $result['name'];
          $complementaryservicetype->code = $result['code'];
          $complementaryservicetype->father_code = $result['father_code'];
          $complementaryservicetype->description = $result['description'];
          $complementaryservicetype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}