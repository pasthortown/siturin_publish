<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\EstablishmentPropertyType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EstablishmentPropertyTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(EstablishmentPropertyType::get(),200);
       } else {
          $establishmentpropertytype = EstablishmentPropertyType::findOrFail($id);
          $attach = [];
          return response()->json(["EstablishmentPropertyType"=>$establishmentpropertytype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(EstablishmentPropertyType::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentpropertytype = new EstablishmentPropertyType();
          $lastEstablishmentPropertyType = EstablishmentPropertyType::orderBy('id')->get()->last();
          if($lastEstablishmentPropertyType) {
             $establishmentpropertytype->id = $lastEstablishmentPropertyType->id + 1;
          } else {
             $establishmentpropertytype->id = 1;
          }
          $establishmentpropertytype->name = $result['name'];
          $establishmentpropertytype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentpropertytype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishmentpropertytype = EstablishmentPropertyType::where('id',$result['id'])->update([
             'name'=>$result['name'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishmentpropertytype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return EstablishmentPropertyType::destroy($id);
    }

    function backup(Request $data)
    {
       $establishmentpropertytypes = EstablishmentPropertyType::get();
       $toReturn = [];
       foreach( $establishmentpropertytypes as $establishmentpropertytype) {
          $attach = [];
          array_push($toReturn, ["EstablishmentPropertyType"=>$establishmentpropertytype, "attach"=>$attach]);
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
         $result = $row['EstablishmentPropertyType'];
         $exist = EstablishmentPropertyType::where('id',$result['id'])->first();
         if ($exist) {
           EstablishmentPropertyType::where('id', $result['id'])->update([
             'name'=>$result['name'],
           ]);
         } else {
          $establishmentpropertytype = new EstablishmentPropertyType();
          $establishmentpropertytype->id = $result['id'];
          $establishmentpropertytype->name = $result['name'];
          $establishmentpropertytype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}