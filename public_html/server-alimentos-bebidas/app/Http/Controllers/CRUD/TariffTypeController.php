<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\TariffType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TariffTypeController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(TariffType::get(),200);
       } else {
          $tarifftype = TariffType::findOrFail($id);
          $attach = [];
          return response()->json(["TariffType"=>$tarifftype, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(TariffType::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $tarifftype = new TariffType();
          $lastTariffType = TariffType::orderBy('id')->get()->last();
          if($lastTariffType) {
             $tarifftype->id = $lastTariffType->id + 1;
          } else {
             $tarifftype->id = 1;
          }
          $tarifftype->name = $result['name'];
          $tarifftype->code = $result['code'];
          $tarifftype->father_code = $result['father_code'];
          $tarifftype->is_reference = $result['is_reference'];
          $tarifftype->factor = $result['factor'];
          $tarifftype->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($tarifftype,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $tarifftype = TariffType::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
             'is_reference'=>$result['is_reference'],
             'factor'=>$result['factor'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($tarifftype,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return TariffType::destroy($id);
    }

    function backup(Request $data)
    {
       $tarifftypes = TariffType::get();
       $toReturn = [];
       foreach( $tarifftypes as $tarifftype) {
          $attach = [];
          array_push($toReturn, ["TariffType"=>$tarifftype, "attach"=>$attach]);
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
         $result = $row['TariffType'];
         $exist = TariffType::where('id',$result['id'])->first();
         if ($exist) {
           TariffType::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'code'=>$result['code'],
             'father_code'=>$result['father_code'],
             'is_reference'=>$result['is_reference'],
             'factor'=>$result['factor'],
           ]);
         } else {
          $tarifftype = new TariffType();
          $tarifftype->id = $result['id'];
          $tarifftype->name = $result['name'];
          $tarifftype->code = $result['code'];
          $tarifftype->father_code = $result['father_code'];
          $tarifftype->is_reference = $result['is_reference'];
          $tarifftype->factor = $result['factor'];
          $tarifftype->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}