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
          return response()->json(Register::orderBy('as_turistic_date', 'ASC')->get(),200);
       } else {
          $register = Register::findOrFail($id);
          $attach = [];
          return response()->json(["Register"=>$register, "attach"=>$attach],200);
       }
    }

    function get_by_register_code(Request $data)
    {
       $code = $data['code'];
       return response()->json(Register::where('register_code', $code)->first(),200);
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Register::paginate($size),200);
    }

    function search_by_ruc(Request $data) {
      $register = Register::where('ruc',$data['ruc'])->orderBy('as_turistic_date', 'ASC')->get();
      if($register) {
        return response()->json($register,200);
      } else {
        return response()->json(0,200);
      }
    }

    function getActivities() {
      $activities = Register::select('activity')->distinct()->get();
      if($activities) {
        return response()->json($activities,200);
      } else {
        return response()->json(0,200);
      }
    }

    function search_filtered(Request $data) {
      $register = Register::where('activity', $data['activity'])->orderBy('as_turistic_date', 'ASC')->get();
      if($register) {
        return response()->json($register,200);
      } else {
        return response()->json(0,200);
      }
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
          $register->ruc = $result['ruc'];
          $register->comercial_name = $result['comercial_name'];
          $register->register_code = $result['register_code'];
          $register->as_turistic_date = $result['as_turistic_date'];
          $register->activity = $result['activity'];
          $register->category = $result['category'];
          $register->classification = $result['classification'];
          $register->legal_representant_name = $result['legal_representant_name'];
          $register->legal_representant_identification = $result['legal_representant_identification'];
          $register->establishment_property_type = $result['establishment_property_type'];
          $register->organization_type = $result['organization_type'];
          $register->ubication_main = $result['ubication_main'];
          $register->ubication_sencond = $result['ubication_sencond'];
          $register->ubication_third = $result['ubication_third'];
          $register->address = $result['address'];
          $register->main_phone_number = $result['main_phone_number'];
          $register->secondary_phone_number = $result['secondary_phone_number'];
          $register->email = $result['email'];
          $register->web = $result['web'];
          $register->system_source = $result['system_source'];
          $register->georeference_latitude = $result['georeference_latitude'];
          $register->georeference_longitude = $result['georeference_longitude'];
          $register->establishment_ruc_code = $result['establishment_ruc_code'];
          $register->max_capacity = $result['max_capacity'];
          $register->max_areas = $result['max_areas'];
          $register->total_male = $result['total_male'];
          $register->total_female = $result['total_female'];
          $register->ruc_state = $result['ruc_state'];
          $register->max_beds = $result['max_beds'];
          $register->establishment_state = $result['establishment_state'];
          $register->save();
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
             'ruc'=>$result['ruc'],
             'comercial_name'=>$result['comercial_name'],
             'register_code'=>$result['register_code'],
             'as_turistic_date'=>$result['as_turistic_date'],
             'activity'=>$result['activity'],
             'category'=>$result['category'],
             'classification'=>$result['classification'],
             'legal_representant_name'=>$result['legal_representant_name'],
             'legal_representant_identification'=>$result['legal_representant_identification'],
             'establishment_property_type'=>$result['establishment_property_type'],
             'organization_type'=>$result['organization_type'],
             'ubication_main'=>$result['ubication_main'],
             'ubication_sencond'=>$result['ubication_sencond'],
             'ubication_third'=>$result['ubication_third'],
             'address'=>$result['address'],
             'main_phone_number'=>$result['main_phone_number'],
             'secondary_phone_number'=>$result['secondary_phone_number'],
             'email'=>$result['email'],
             'web'=>$result['web'],
             'system_source'=>$result['system_source'],
             'georeference_latitude'=>$result['georeference_latitude'],
             'georeference_longitude'=>$result['georeference_longitude'],
             'establishment_ruc_code'=>$result['establishment_ruc_code'],
             'max_capacity'=>$result['max_capacity'],
             'max_areas'=>$result['max_areas'],
             'total_male'=>$result['total_male'],
             'total_female'=>$result['total_female'],
             'ruc_state'=>$result['ruc_state'],
             'max_beds'=>$result['max_beds'],
             'establishment_state'=>$result['establishment_state'],
          ]);
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
             'ruc'=>$result['ruc'],
             'comercial_name'=>$result['comercial_name'],
             'register_code'=>$result['register_code'],
             'as_turistic_date'=>$result['as_turistic_date'],
             'activity'=>$result['activity'],
             'category'=>$result['category'],
             'classification'=>$result['classification'],
             'legal_representant_name'=>$result['legal_representant_name'],
             'legal_representant_identification'=>$result['legal_representant_identification'],
             'establishment_property_type'=>$result['establishment_property_type'],
             'organization_type'=>$result['organization_type'],
             'ubication_main'=>$result['ubication_main'],
             'ubication_sencond'=>$result['ubication_sencond'],
             'ubication_third'=>$result['ubication_third'],
             'address'=>$result['address'],
             'main_phone_number'=>$result['main_phone_number'],
             'secondary_phone_number'=>$result['secondary_phone_number'],
             'email'=>$result['email'],
             'web'=>$result['web'],
             'system_source'=>$result['system_source'],
             'georeference_latitude'=>$result['georeference_latitude'],
             'georeference_longitude'=>$result['georeference_longitude'],
             'establishment_ruc_code'=>$result['establishment_ruc_code'],
             'max_capacity'=>$result['max_capacity'],
             'max_areas'=>$result['max_areas'],
             'total_male'=>$result['total_male'],
             'total_female'=>$result['total_female'],
             'ruc_state'=>$result['ruc_state'],
             'max_beds'=>$result['max_beds'],
             'establishment_state'=>$result['establishment_state'],
           ]);
         } else {
          $register = new Register();
          $register->id = $result['id'];
          $register->ruc = $result['ruc'];
          $register->comercial_name = $result['comercial_name'];
          $register->register_code = $result['register_code'];
          $register->as_turistic_date = $result['as_turistic_date'];
          $register->activity = $result['activity'];
          $register->category = $result['category'];
          $register->classification = $result['classification'];
          $register->legal_representant_name = $result['legal_representant_name'];
          $register->legal_representant_identification = $result['legal_representant_identification'];
          $register->establishment_property_type = $result['establishment_property_type'];
          $register->organization_type = $result['organization_type'];
          $register->ubication_main = $result['ubication_main'];
          $register->ubication_sencond = $result['ubication_sencond'];
          $register->ubication_third = $result['ubication_third'];
          $register->address = $result['address'];
          $register->main_phone_number = $result['main_phone_number'];
          $register->secondary_phone_number = $result['secondary_phone_number'];
          $register->email = $result['email'];
          $register->web = $result['web'];
          $register->system_source = $result['system_source'];
          $register->georeference_latitude = $result['georeference_latitude'];
          $register->georeference_longitude = $result['georeference_longitude'];
          $register->establishment_ruc_code = $result['establishment_ruc_code'];
          $register->max_capacity = $result['max_capacity'];
          $register->max_areas = $result['max_areas'];
          $register->total_male = $result['total_male'];
          $register->total_female = $result['total_female'];
          $register->ruc_state = $result['ruc_state'];
          $register->max_beds = $result['max_beds'];
          $register->establishment_state = $result['establishment_state'];
          $register->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}

