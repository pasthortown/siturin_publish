<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Tariff;
use App\Register;
use App\Capacity;
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

    function tarifario_rack(Request $data) {
      $result = $data->json()->all(); 
      $tarifario_rack = $result['tarifario_rack'];
      $capacidades = $result['capacidades'];
      $register_id = $result['register_id'];
      $register = Register::where('id', $register_id)->first();
      $capacities_on_register_old = $register->Capacities()->get();
      foreach( $capacities_on_register_old as $capacity_old ) {
         $register->Capacities()->detach($capacity_old->id);
         Capacity::destroy($capacity_old->id);
      }
      foreach($capacidades as $capacityToRegister) {
         $capacity = new Capacity();
         $lastCapacity = Capacity::orderBy('id')->get()->last();
         if($lastCapacity) {
            $capacity->id = $lastCapacity->id + 1;
         } else {
            $capacity->id = 1;
         }
         $capacity->quantity = $capacityToRegister['quantity'];
         $capacity->capacity_type_id = $capacityToRegister['capacity_type_id'];
         $capacity->save();
         $register->Capacities()->attach($capacity->id);
      }
      foreach($tarifario_rack as $tarifa) {
         if($tarifa['id'] == 0) {
            $tariff = new Tariff();
            $lastTariff = Tariff::orderBy('id')->get()->last();
            if($lastTariff) {
               $tariff->id = $lastTariff->id + 1;
            } else {
               $tariff->id = 1;
            }
            $tariff->price = $tarifa['price'];
            $tariff->year = $tarifa['year'];
            $tariff->register_id = $tarifa['register_id'];
            $tariff->tariff_type_id = $tarifa['tariff_type_id'];
            $tariff->capacity_type_id = $tarifa['capacity_type_id'];
            $tariff->register_id = $register->id;
            $tariff->state_id = 1;
            $tariff->save();
         } else {
            $tariff = Tariff::where('id',$tarifa['id'])->update([
               'price'=>$tarifa['price'],
               'year'=>$tarifa['year'],
               'state_id'=>1,
               'tariff_type_id'=>$tarifa['tariff_type_id'],
               'capacity_type_id'=>$tarifa['capacity_type_id'],
               'register_id'=>$tarifa['register_id'],
            ]);
         }
      }
      return response()->json(["Capacidades:"=>$capacidades,"Tarifario Rack:"=>$tarifario_rack], 200);
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