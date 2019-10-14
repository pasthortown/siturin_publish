<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\TaxPayerType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaxPayerTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(TaxPayerType::get(),200);
       } else {
          $taxpayertype = TaxPayerType::findOrFail($id);
          $attach = [];
          return response()->json(["TaxPayerType"=>$taxpayertype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(TaxPayerType::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $taxpayertype = new TaxPayerType();
          $lastTaxPayerType = TaxPayerType::orderBy('id')->get()->last();
          if($lastTaxPayerType) {
             $taxpayertype->id = $lastTaxPayerType->id + 1;
          } else {
             $taxpayertype->id = 1;
          }
          $taxpayertype->name = $result['name'];
          $taxpayertype->description = $result['description'];
          $taxpayertype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($taxpayertype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $taxpayertype = TaxPayerType::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($taxpayertype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return TaxPayerType::destroy($id);
    }

    function backup(Request $data)
    {
       $taxpayertypes = TaxPayerType::get();
       $toReturn = [];
       foreach( $taxpayertypes as $taxpayertype) {
          $attach = [];
          array_push($toReturn, ["TaxPayerType"=>$taxpayertype, "attach"=>$attach]);
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
         $result = $row['TaxPayerType'];
         $exist = TaxPayerType::where('id',$result['id'])->first();
         if ($exist) {
           TaxPayerType::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
           ]);
         } else {
          $taxpayertype = new TaxPayerType();
          $taxpayertype->id = $result['id'];
          $taxpayertype->name = $result['name'];
          $taxpayertype->description = $result['description'];
          $taxpayertype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}