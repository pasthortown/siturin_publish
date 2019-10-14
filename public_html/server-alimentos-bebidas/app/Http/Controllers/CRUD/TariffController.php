<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Tariff;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TariffController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Tariff::get(),200);
       } else {
          $tariff = Tariff::findOrFail($id);
          $attach = [];
          return response()->json(["Tariff"=>$tariff, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Tariff::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $tariff = new Tariff();
          $lastTariff = Tariff::orderBy('id')->get()->last();
          if($lastTariff) {
             $tariff->id = $lastTariff->id + 1;
          } else {
             $tariff->id = 1;
          }
          $tariff->price = $result['price'];
          $tariff->year = $result['year'];
          $tariff->state_id = $result['state_id'];
          $tariff->tariff_type_id = $result['tariff_type_id'];
          $tariff->capacity_type_id = $result['capacity_type_id'];
          $tariff->register_id = $result['register_id'];
          $tariff->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($tariff,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $tariff = Tariff::where('id',$result['id'])->update([
             'price'=>$result['price'],
             'year'=>$result['year'],
             'state_id'=>$result['state_id'],
             'tariff_type_id'=>$result['tariff_type_id'],
             'capacity_type_id'=>$result['capacity_type_id'],
             'register_id'=>$result['register_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($tariff,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Tariff::destroy($id);
    }

    function backup(Request $data)
    {
       $tariffs = Tariff::get();
       $toReturn = [];
       foreach( $tariffs as $tariff) {
          $attach = [];
          array_push($toReturn, ["Tariff"=>$tariff, "attach"=>$attach]);
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
         $result = $row['Tariff'];
         $exist = Tariff::where('id',$result['id'])->first();
         if ($exist) {
           Tariff::where('id', $result['id'])->update([
             'price'=>$result['price'],
             'year'=>$result['year'],
             'state_id'=>$result['state_id'],
             'tariff_type_id'=>$result['tariff_type_id'],
             'capacity_type_id'=>$result['capacity_type_id'],
             'register_id'=>$result['register_id'],
           ]);
         } else {
          $tariff = new Tariff();
          $tariff->id = $result['id'];
          $tariff->price = $result['price'];
          $tariff->year = $result['year'];
          $tariff->state_id = $result['state_id'];
          $tariff->tariff_type_id = $result['tariff_type_id'];
          $tariff->capacity_type_id = $result['capacity_type_id'];
          $tariff->register_id = $result['register_id'];
          $tariff->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}