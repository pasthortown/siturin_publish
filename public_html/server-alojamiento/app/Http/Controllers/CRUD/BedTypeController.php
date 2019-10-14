<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\BedType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BedTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(BedType::get(),200);
       } else {
          $bedtype = BedType::findOrFail($id);
          $attach = [];
          return response()->json(["BedType"=>$bedtype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(BedType::paginate($size),200);
    }

    function filtered(Request $data)
    {
       $register_type_id = $data['register_type_id'];
       return response()->json(BedType::where('register_type_id', $register_type_id)->get(),200);
    }
    
    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $bedtype = new BedType();
          $lastBedType = BedType::orderBy('id')->get()->last();
          if($lastBedType) {
             $bedtype->id = $lastBedType->id + 1;
          } else {
             $bedtype->id = 1;
          }
          $bedtype->name = $result['name'];
          $bedtype->register_type_id = $result['register_type_id'];
          $bedtype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($bedtype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $bedtype = BedType::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'register_type_id'=>$result['register_type_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($bedtype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return BedType::destroy($id);
    }

    function backup(Request $data)
    {
       $bedtypes = BedType::get();
       $toReturn = [];
       foreach( $bedtypes as $bedtype) {
          $attach = [];
          array_push($toReturn, ["BedType"=>$bedtype, "attach"=>$attach]);
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
         $result = $row['BedType'];
         $exist = BedType::where('id',$result['id'])->first();
         if ($exist) {
           BedType::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'register_type_id'=>$result['register_type_id'],
           ]);
         } else {
          $bedtype = new BedType();
          $bedtype->id = $result['id'];
          $bedtype->name = $result['name'];
          $bedtype->register_type_id = $result['register_type_id'];
          $bedtype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}