<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Register;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegisterController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Register::get(),200);
       } else {
          $register = Register::findOrFail($id);
          $attach = [];
          $capacities_on_register = $register->Capacities()->get();
          array_push($attach, ["capacities_on_register"=>$capacities_on_register]);
          return response()->json(["Register"=>$register, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Register::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $register = new Register();
          $lastRegister = Register::orderBy('id')->get()->last();
          if($lastRegister) {
             $register->id = $lastRegister->id + 1;
          } else {
             $register->id = 1;
          }
          $register->code = $result['code'];
          $register->autorized_complementary_capacities = $result['autorized_complementary_capacities'];
          $register->establishment_id = $result['establishment_id'];
          $register->autorized_complementary_food_capacities = $result['autorized_complementary_food_capacities'];
          $register->register_type_id = $result['register_type_id'];
          $register->save();
          $capacities_on_register = $result['capacities_on_register'];
          foreach( $capacities_on_register as $capacity) {
             $register->Capacities()->attach($capacity['id']);
          }
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($register,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $register = Register::where('id',$result['id'])->update([
             'code'=>$result['code'],
             'autorized_complementary_capacities'=>$result['autorized_complementary_capacities'],
             'establishment_id'=>$result['establishment_id'],
             'autorized_complementary_food_capacities'=>$result['autorized_complementary_food_capacities'],
             'register_type_id'=>$result['register_type_id'],
          ]);
          $register = Register::where('id',$result['id'])->first();
          $capacities_on_register = $result['capacities_on_register'];
          $capacities_on_register_old = $register->Capacities()->get();
          foreach( $capacities_on_register_old as $capacity_old ) {
             $delete = true;
             foreach( $capacities_on_register as $capacity ) {
                if ( $capacity_old->id === $capacity['id'] ) {
                   $delete = false;
                }
             }
             if ( $delete ) {
                $register->Capacities()->detach($capacity_old->id);
             }
          }
          foreach( $capacities_on_register as $capacity ) {
             $add = true;
             foreach( $capacities_on_register_old as $capacity_old) {
                if ( $capacity_old->id === $capacity['id'] ) {
                   $add = false;
                }
             }
             if ( $add ) {
                $register->Capacities()->attach($capacity['id']);
             }
          }
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($register,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Register::destroy($id);
    }

    function backup(Request $data)
    {
       $registers = Register::get();
       $toReturn = [];
       foreach( $registers as $register) {
          $attach = [];
          $capacities_on_register = $register->Capacities()->get();
          array_push($attach, ["capacities_on_register"=>$capacities_on_register]);
          array_push($toReturn, ["Register"=>$register, "attach"=>$attach]);
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
         $result = $row['Register'];
         $exist = Register::where('id',$result['id'])->first();
         if ($exist) {
           Register::where('id', $result['id'])->update([
             'code'=>$result['code'],
             'autorized_complementary_capacities'=>$result['autorized_complementary_capacities'],
             'establishment_id'=>$result['establishment_id'],
             'autorized_complementary_food_capacities'=>$result['autorized_complementary_food_capacities'],
             'register_type_id'=>$result['register_type_id'],
           ]);
         } else {
          $register = new Register();
          $register->id = $result['id'];
          $register->code = $result['code'];
          $register->autorized_complementary_capacities = $result['autorized_complementary_capacities'];
          $register->establishment_id = $result['establishment_id'];
          $register->autorized_complementary_food_capacities = $result['autorized_complementary_food_capacities'];
          $register->register_type_id = $result['register_type_id'];
          $register->save();
         }
         $register = Register::where('id',$result['id'])->first();
         $capacities_on_register = [];
         foreach($row['attach'] as $attach){
            $capacities_on_register = $attach['capacities_on_register'];
         }
         $capacities_on_register_old = $register->Capacities()->get();
         foreach( $capacities_on_register_old as $capacity_old ) {
            $delete = true;
            foreach( $capacities_on_register as $capacity ) {
               if ( $capacity_old->id === $capacity['id'] ) {
                  $delete = false;
               }
            }
            if ( $delete ) {
               $register->Capacities()->detach($capacity_old->id);
            }
         }
         foreach( $capacities_on_register as $capacity ) {
            $add = true;
            foreach( $capacities_on_register_old as $capacity_old) {
               if ( $capacity_old->id === $capacity['id'] ) {
                  $add = false;
               }
            }
            if ( $add ) {
               $register->Capacities()->attach($capacity['id']);
            }
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}