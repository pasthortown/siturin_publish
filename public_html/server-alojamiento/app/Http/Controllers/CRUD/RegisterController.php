<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Register;
use App\Capacity;
use App\Bed;
use App\Tariff;
use App\RegisterRequisite;
use App\ComplementaryServiceType;
use App\RegisterState;
use App\State;
use App\ComplementaryServiceFood;
use App\RegisterType;
use App\Approval;
use App\ApprovalState;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegisterController extends Controller
{
    function get(Request $data)
    {
      $id = $data['id'];
      if ($id == null) {
         $registers = Register::orderBy('updated_at', 'DESC')->get();
         return response()->json($registers, 200);
      } else {
         $register = Register::findOrFail($id);
         $attach = [];
         $complementary_service_types_on_register = $register->ComplementaryServiceTypes()->get();
         array_push($attach, ["complementary_service_types_on_register"=>$complementary_service_types_on_register]);
         $capacities_on_register = $register->Capacities()->get();
         array_push($attach, ["capacities_on_register"=>$capacities_on_register]);
         $complementary_service_foods_on_register = $register->ComplementaryServiceFoods()->get();
         array_push($attach, ["complementary_service_foods_on_register"=>$complementary_service_foods_on_register]);
         return response()->json(["Register"=>$register, "attach"=>$attach],200);
      }
    }

    function get_by_register_code(Request $data) {
      $code = $data['code'];
      $toReturn = Register::where('code', $code)->first();
      return response()->json($toReturn, 200);
    }

    function get_requisites_set_by_user(Request $data) {
       $register_id = $data['register_id'];
       $toReturn = RegisterRequisite::join('requisites', 'requisites.id', '=', 'register_requisites.requisite_id')->where('register_id', $register_id)->select('register_requisites.*', 'requisites.name as requisite_name', 'requisites.father_code as requisite_father_code')->get();
       return response()->json($toReturn, 200);
    }

    function paginate(Request $data)
    {
      $size = $data['size'];
      return response()->json(Register::paginate($size),200);
    }

    function by_inspector_id(Request $data) {
      $registers = Register::join('approval_states', 'approval_states.register_id', '=', 'registers.id')->where('approval_states.id_user',$data['id'])->select('registers.*', 'approval_states.date_assigment')->distinct()->orderBy('registers.updated_at', 'DESC')->orderBy('approval_states.date_assigment', 'ASC')->get();
      return response()->json($registers, 200);
    }

    function by_financial_id(Request $data) {
      $registers = Register::join('approval_states', 'approval_states.register_id', '=', 'registers.id')->where('approval_states.id_user',$data['id'])->select('registers.*', 'approval_states.date_assigment')->distinct()->orderBy('registers.updated_at', 'DESC')->orderBy('approval_states.date_assigment', 'ASC')->get();
      return response()->json($registers, 200);
    }

    function get_registers_by_ruc(Request $data) {
      $token = $data->header('api_token');
      $number = $data['ruc_number'];
      try{
         $establishments = json_decode($this->httpGet(env('API_BASE').'establishment/get_by_ruc?size=1000&ruc='.$number, null, null, $token))->data;
      } catch (Exception $e) {
         $establishments = [];
      }
      $toReturn = [];
      foreach($establishments as $establishment) {
         $registers_on_establishment = Register::where('establishment_id', $establishment->id)->orderBy('created_at', 'ASC')->get();
         foreach($registers_on_establishment as $register){
            $register_type = RegisterType::where('id', $register->register_type_id)->first();
            $register_category = RegisterType::where('code', $register_type->father_code)->first();
            $status_register = RegisterState::where('register_id', $register->id)->orderBy('created_at', 'DESC')->first();
            $status = State::where('id',$status_register->state_id)->first();
            array_push($toReturn, ["register"=>$register, "establishment"=>$establishment, "status_register"=>$status_register, "status"=>$status, "type"=>["register_type"=>$register_type, "register_category"=>$register_category]]);
         }
      }
      return response()->json($toReturn, 200);
    }

    function set_register_code(Request $data) {
      $result = $data->json()->all();
      $code = $result['code'];
      try{
         DB::beginTransaction();
         $register = Register::where('id', $result['id'])->update([
            'code'=>$result['code'],
         ]);
         DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json($register,200);
    }

    function get_tarifario(Request $data) {
      $result = $data->json()->all();
      $tarifario_rack = Tariff::where('register_id', $result['register_id'])->get();
      return response()->json($tarifario_rack,200);
    }

    function get_register_data(Request $data) {
      $id = $data['id'];
      $register = Register::where('id', $id)->first();
      $register_type = RegisterType::where('id', $register->register_type_id)->first();
      $register_category = RegisterType::where('code', $register_type->father_code)->first();
      $status_register = RegisterState::where('register_id', $register->id)->orderBy('created_at', 'DESC')->first();
      $requisites = RegisterRequisite::where('register_id', $register->id)->orderBy('requisite_id', 'ASC')->get();
      $capacities_on_register = $register->Capacities()->get();
      $complementary_service_types_on_register = $register->ComplementaryServiceTypes()->get();
      $complementary_service_foods_on_register = $register->ComplementaryServiceFoods()->get();
      $toReturn = ["register"=>$register,
                   "requisites"=>$requisites,
                   "status"=>$status_register,
                   "register_category"=>$register_category,
                   "capacities_on_register"=>$capacities_on_register,
                   "complementary_service_types_on_register"=>$complementary_service_types_on_register,
                   "complementary_service_foods_on_register"=>$complementary_service_foods_on_register,
                  ];
      return response()->json($toReturn, 200);
    }

    function register_register_data(Request $data) {
      $result = $data->json()->all();
      $id = $result['id'];
      $tarifario_rack = $result['tarifario_rack'];
      $capacities_on_register = $result['capacities_on_register'];
      $complementary_service_types_on_register = $result['complementary_service_types_on_register'];
      if(!$result['autorized_complementary_capacities']){
         $complementary_service_types_on_register = [];
      }
      $complementary_service_foods_on_register = $result['complementary_service_foods_on_register'];
      if(!$result['autorized_complementary_food_capacities']){
         $complementary_service_foods_on_register = [];
      }
      $requisites = $result['requisites'];
      $status_id = $result['status'];
      if($id == 0) {
         DB::beginTransaction();
         $register = new Register();
         $lastRegister = Register::orderBy('id')->get()->last();
         if($lastRegister) {
            $register->id = $lastRegister->id + 1;
         } else {
            $register->id = 1;
         }
         $register->code = $result['code'];
         $register->autorized_complementary_capacities = $result['autorized_complementary_capacities'];
         $register->autorized_complementary_food_capacities = $result['autorized_complementary_food_capacities'];
         $register->establishment_id = $result['establishment_id'];
         $register->register_type_id = $result['register_type_id'];
         $register->save();
         foreach($complementary_service_foods_on_register as $complementary_service_food_on_register) {
            $complementaryservicefood = new ComplementaryServiceFood();
            $lastComplementaryServiceFood = ComplementaryServiceFood::orderBy('id')->get()->last();
            if($lastComplementaryServiceFood) {
               $complementaryservicefood->id = $lastComplementaryServiceFood->id + 1;
            } else {
               $complementaryservicefood->id = 1;
            }
            $complementaryservicefood->quantity_tables = $complementary_service_food_on_register['quantity_tables'];
            $complementaryservicefood->quantity_chairs = $complementary_service_food_on_register['quantity_chairs'];
            $complementaryservicefood->complementary_service_food_type_id = $complementary_service_food_on_register['complementary_service_food_type_id'];
            $complementaryservicefood->save();
            $register->ComplementaryServiceFoods()->attach($complementaryservicefood->id);
         }
         foreach($complementary_service_types_on_register as $complementary_service_type) {
            $register->ComplementaryServiceTypes()->attach($complementary_service_type['id']);
         }
         foreach($capacities_on_register as $capacityToRegister) {
            $capacity = new Capacity();
            $lastCapacity = Capacity::orderBy('id')->get()->last();
            if($lastCapacity) {
               $capacity->id = $lastCapacity->id + 1;
            } else {
               $capacity->id = 1;
            }
            $capacity->quantity = $capacityToRegister['quantity'];
            $capacity->capacity_type_id = $capacityToRegister['capacity_type_id'];
            $capacity->max_beds = $capacityToRegister['max_beds'];
            $capacity->max_spaces = $capacityToRegister['max_spaces'];
            $capacity->save();
            $beds_on_capacity = $capacityToRegister['beds_on_capacity'];
            foreach($beds_on_capacity as $bed_to_add){
               $bed = new Bed();
               $lastBed = Bed::orderBy('id')->get()->last();
               if($lastBed) {
                  $bed->id = $lastBed->id + 1;
               } else {
                  $bed->id = 1;
               }
               $bed->quantity = $bed_to_add['quantity'];
               $bed->bed_type_id = $bed_to_add['bed_type_id'];
               $bed->save();
               $capacity->Beds()->attach($bed->id);
            }
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
         foreach($requisites as $requisite_to_add) {
            $registerrequisite = new RegisterRequisite();
            $lastRegisterRequisite = RegisterRequisite::orderBy('id')->get()->last();
            if($lastRegisterRequisite) {
               $registerrequisite->id = $lastRegisterRequisite->id + 1;
            } else {
               $registerrequisite->id = 1;
            }
            $registerrequisite->value = $requisite_to_add['value'];
            $registerrequisite->fullfill = $requisite_to_add['fullfill'];
            $registerrequisite->requisite_id = $requisite_to_add['requisite_id'];
            $registerrequisite->register_id = $register->id;
            $registerrequisite->save();  
         }
         $registerstate = new RegisterState();
         $lastRegisterState = RegisterState::orderBy('id')->get()->last();
         if($lastRegisterState) {
            $registerstate->id = $lastRegisterState->id + 1;
         } else {
            $registerstate->id = 1;
         }
         $registerstate->justification = 'Solicitud de Registro Elaborada en la fecha: ' . date('Y-m-d h:i:s A');
         $registerstate->register_id = $register->id;
         $registerstate->state_id = $status_id;
         $registerstate->save();
         $approvals = Approval::get();
         foreach($approvals as $approval) {
            $approvalstate = new ApprovalState();
            $lastApprovalState = ApprovalState::orderBy('id')->get()->last();
             if($lastApprovalState) {
                $approvalstate->id = $lastApprovalState->id + 1;
             } else {
                $approvalstate->id = 1;
            }
            $approvalstate->value = false;
            $approvalstate->id_user = 0;
            $approvalstate->approval_id = $approval->id;
            $approvalstate->register_id = $register->id;
            $approvalstate->save();      
         }
         DB::commit();
         return response()->json($register,200);
      }else {
         DB::beginTransaction();
         $register = Register::where('id', $result['id'])->update([
            'code'=>$result['code'],
            'autorized_complementary_capacities'=>$result['autorized_complementary_capacities'],
            'autorized_complementary_food_capacities'=>$result['autorized_complementary_food_capacities'],
            'establishment_id'=>$result['establishment_id'],
            'register_type_id'=>$result['register_type_id'],
         ]);
         $register = Register::where('id', $result['id'])->first();
         $complementary_service_types_on_register_old = $register->ComplementaryServiceTypes()->get();
         foreach( $complementary_service_types_on_register_old as $complementary_service_type_old ) {
            $delete = true;
            foreach( $complementary_service_types_on_register as $complementary_service_type ) {
               if ( $complementary_service_type_old->id === $complementary_service_type['id'] ) {
                  $delete = false;
               }
            }
            if ( $delete ) {
               $register->ComplementaryServiceTypes()->detach($complementary_service_type_old->id);
            }
         }
         foreach( $complementary_service_types_on_register as $complementary_service_type ) {
            $add = true;
            foreach( $complementary_service_types_on_register_old as $complementary_service_type_old) {
               if ( $complementary_service_type_old->id === $complementary_service_type['id'] ) {
                  $add = false;
               }
            }
            if ( $add ) {
               $register->ComplementaryServiceTypes()->attach($complementary_service_type['id']);
            }
         }
         $capacities_on_register_old = $register->Capacities()->get();
         foreach( $capacities_on_register_old as $capacity_old ) {
            $register->Capacities()->detach($capacity_old->id);
            Capacity::destroy($capacity_old->id);
         }
         foreach($capacities_on_register as $capacityToRegister) {
            $capacity = new Capacity();
            $lastCapacity = Capacity::orderBy('id')->get()->last();
            if($lastCapacity) {
               $capacity->id = $lastCapacity->id + 1;
            } else {
               $capacity->id = 1;
            }
            $capacity->quantity = $capacityToRegister['quantity'];
            $capacity->capacity_type_id = $capacityToRegister['capacity_type_id'];
            $capacity->max_beds = $capacityToRegister['max_beds'];
            $capacity->max_spaces = $capacityToRegister['max_spaces'];
            $capacity->save();
            $beds_on_capacity = $capacityToRegister['beds_on_capacity'];
            foreach($beds_on_capacity as $bed_to_add){
               $bed = new Bed();
               $lastBed = Bed::orderBy('id')->get()->last();
               if($lastBed) {
                  $bed->id = $lastBed->id + 1;
               } else {
                  $bed->id = 1;
               }
               $bed->quantity = $bed_to_add['quantity'];
               $bed->bed_type_id = $bed_to_add['bed_type_id'];
               $bed->save();
               $capacity->Beds()->attach($bed->id);
            }
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
         $complementary_service_foods_on_register_old = $register->ComplementaryServiceFoods()->get();
         foreach( $complementary_service_foods_on_register_old as $complementary_service_food_on_register_old ) {
            $register->ComplementaryServiceFoods()->detach($complementary_service_food_on_register_old->id);
            ComplementaryServiceFood::destroy($complementary_service_food_on_register_old->id);
         }
         foreach($complementary_service_foods_on_register as $complementary_service_food_on_register) {
            $complementaryservicefood = new ComplementaryServiceFood();
            $lastComplementaryServiceFood = ComplementaryServiceFood::orderBy('id')->get()->last();
            if($lastComplementaryServiceFood) {
               $complementaryservicefood->id = $lastComplementaryServiceFood->id + 1;
            } else {
               $complementaryservicefood->id = 1;
            }
            $complementaryservicefood->quantity_tables = $complementary_service_food_on_register['quantity_tables'];
            $complementaryservicefood->quantity_chairs = $complementary_service_food_on_register['quantity_chairs'];
            $complementaryservicefood->complementary_service_food_type_id = $complementary_service_food_on_register['complementary_service_food_type_id'];
            $complementaryservicefood->save();
            $register->ComplementaryServiceFoods()->attach($complementaryservicefood->id);
         }
         $preview_requisites = RegisterRequisite::where('register_id', $register->id)->get();
         foreach($preview_requisites as $preview_requisite){
            RegisterRequisite::destroy($preview_requisite->id);
         } 
         foreach($requisites as $requisite_to_add) {
            $registerrequisite = new RegisterRequisite();
            $lastRegisterRequisite = RegisterRequisite::orderBy('id')->get()->last();
            if($lastRegisterRequisite) {
               $registerrequisite->id = $lastRegisterRequisite->id + 1;
            } else {
               $registerrequisite->id = 1;
            }
            $registerrequisite->value = $requisite_to_add['value'];
            $registerrequisite->fullfill = $requisite_to_add['fullfill'];
            $registerrequisite->requisite_id = $requisite_to_add['requisite_id'];
            $registerrequisite->register_id = $result['id'];
            $registerrequisite->save();  
         }
         $registerstate = new RegisterState();
         $lastRegisterState = RegisterState::orderBy('id')->get()->last();
         if($lastRegisterState) {
            $registerstate->id = $lastRegisterState->id + 1;
         } else {
            $registerstate->id = 1;
         }
         $registerstate->justification = 'Solicitud de Registro Actualizada en la fecha: ' . date('Y-m-d h:i:s A');
         $registerstate->register_id = $register->id;
         $registerstate->state_id = $status_id;
         $registerstate->save();
         $approvalstates = ApprovalState::where('register_id', $register->id)->get();
         foreach($approvalstates as $approvalstate) {
            ApprovalState::destroy($approvalstate->id);
         }
         $approvals = Approval::get();
         foreach($approvals as $approval) {
            $approvalstate = new ApprovalState();
            $lastApprovalState = ApprovalState::orderBy('id')->get()->last();
             if($lastApprovalState) {
                $approvalstate->id = $lastApprovalState->id + 1;
             } else {
                $approvalstate->id = 1;
            }
            $approvalstate->value = false;
            $approvalstate->id_user = 0;
            $approvalstate->approval_id = $approval->id;
            $approvalstate->register_id = $register->id;
            $approvalstate->save();      
         }
         DB::commit();
      } 
      return response()->json($register,200);
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
          $register->autorized_complementary_food_capacities = $result['autorized_complementary_food_capacities'];
          $register->establishment_id = $result['establishment_id'];
          $register->register_type_id = $result['register_type_id'];
          $register->save();
          $complementary_service_types_on_register = $result['complementary_service_types_on_register'];
          foreach( $complementary_service_types_on_register as $complementary_service_type) {
             $register->ComplementaryServiceTypes()->attach($complementary_service_type['id']);
          }
          $capacities_on_register = $result['capacities_on_register'];
          foreach( $capacities_on_register as $capacity) {
             $register->Capacities()->attach($capacity['id']);
          }
          $complementary_service_foods_on_register = $result['complementary_service_foods_on_register'];
          foreach( $complementary_service_foods_on_register as $complementary_service_food) {
             $register->ComplementaryServiceFoods()->attach($complementary_service_food['id']);
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
             'autorized_complementary_food_capacities'=>$result['autorized_complementary_food_capacities'],
             'establishment_id'=>$result['establishment_id'],
             'register_type_id'=>$result['register_type_id'],
          ]);
          $register = Register::where('id',$result['id'])->first();
          $complementary_service_types_on_register = $result['complementary_service_types_on_register'];
          $complementary_service_types_on_register_old = $register->ComplementaryServiceTypes()->get();
          foreach( $complementary_service_types_on_register_old as $complementary_service_type_old ) {
             $delete = true;
             foreach( $complementary_service_types_on_register as $complementary_service_type ) {
                if ( $complementary_service_type_old->id === $complementary_service_type['id'] ) {
                   $delete = false;
                }
             }
             if ( $delete ) {
                $register->ComplementaryServiceTypes()->detach($complementary_service_type_old->id);
             }
          }
          foreach( $complementary_service_types_on_register as $complementary_service_type ) {
             $add = true;
             foreach( $complementary_service_types_on_register_old as $complementary_service_type_old) {
                if ( $complementary_service_type_old->id === $complementary_service_type['id'] ) {
                   $add = false;
                }
             }
             if ( $add ) {
                $register->ComplementaryServiceTypes()->attach($complementary_service_type['id']);
             }
          }
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
          $complementary_service_foods_on_register = $result['complementary_service_foods_on_register'];
          $complementary_service_foods_on_register_old = $register->ComplementaryServiceFoods()->get();
          foreach( $complementary_service_foods_on_register_old as $complementary_service_food_old ) {
             $delete = true;
             foreach( $complementary_service_foods_on_register as $complementary_service_food ) {
                if ( $complementary_service_food_old->id === $complementary_service_food['id'] ) {
                   $delete = false;
                }
             }
             if ( $delete ) {
                $register->ComplementaryServiceFoods()->detach($complementary_service_food_old->id);
             }
          }
          foreach( $complementary_service_foods_on_register as $complementary_service_food ) {
             $add = true;
             foreach( $complementary_service_foods_on_register_old as $complementary_service_food_old) {
                if ( $complementary_service_food_old->id === $complementary_service_food['id'] ) {
                   $add = false;
                }
             }
             if ( $add ) {
                $register->ComplementaryServiceFoods()->attach($complementary_service_food['id']);
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
         $complementary_service_types_on_register = $register->ComplementaryServiceTypes()->get();
         array_push($attach, ["complementary_service_types_on_register"=>$complementary_service_types_on_register]);
         $capacities_on_register = $register->Capacities()->get();
         array_push($attach, ["capacities_on_register"=>$capacities_on_register]);
         $complementary_service_foods_on_register = $register->ComplementaryServiceFoods()->get();
         array_push($attach, ["complementary_service_foods_on_register"=>$complementary_service_foods_on_register]);
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
         $complementary_service_types_on_register = [];
         foreach($row['attach'] as $attach){
            $complementary_service_types_on_register = $attach['complementary_service_types_on_register'];
         }
         $complementary_service_types_on_register_old = $register->ComplementaryServiceTypes()->get();
         foreach( $complementary_service_types_on_register_old as $complementary_service_type_old ) {
            $delete = true;
            foreach( $complementary_service_types_on_register as $complementary_service_type ) {
               if ( $complementary_service_type_old->id === $complementary_service_type['id'] ) {
                  $delete = false;
               }
            }
            if ( $delete ) {
               $register->ComplementaryServiceTypes()->detach($complementary_service_type_old->id);
            }
         }
         foreach( $complementary_service_types_on_register as $complementary_service_type ) {
            $add = true;
            foreach( $complementary_service_types_on_register_old as $complementary_service_type_old) {
               if ( $complementary_service_type_old->id === $complementary_service_type['id'] ) {
                  $add = false;
               }
            }
            if ( $add ) {
               $register->ComplementaryServiceTypes()->attach($complementary_service_type['id']);
            }
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
         $register = Register::where('id',$result['id'])->first();
         $complementary_service_foods_on_register = [];
         foreach($row['attach'] as $attach){
            $complementary_service_foods_on_register = $attach['complementary_service_foods_on_register'];
         }
         $complementary_service_foods_on_register_old = $register->ComplementaryServiceFoods()->get();
         foreach( $complementary_service_foods_on_register_old as $complementary_service_food_old ) {
            $delete = true;
            foreach( $complementary_service_foods_on_register as $complementary_service_food ) {
               if ( $complementary_service_food_old->id === $complementary_service_food['id'] ) {
                  $delete = false;
               }
            }
            if ( $delete ) {
               $register->ComplementaryServiceFoods()->detach($complementary_service_food_old->id);
            }
         }
         foreach( $complementary_service_foods_on_register as $complementary_service_food ) {
            $add = true;
            foreach( $complementary_service_foods_on_register_old as $complementary_service_food_old) {
               if ( $complementary_service_food_old->id === $complementary_service_food['id'] ) {
                  $add = false;
               }
            }
            if ( $add ) {
               $register->ComplementaryServiceFoods()->attach($complementary_service_food['id']);
            }
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }

    protected function httpGet($url, $data=NULL, $headers = NULL, $token) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      if(!empty($data)){
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      }
      $headersSend = array();
      array_push($headersSend, 'api_token:'.$token);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headersSend);
      $response = curl_exec($ch);
      if (curl_error($ch)) {
          trigger_error('Curl Error:' . curl_error($ch));
      }
      curl_close($ch);
      return $response;
   }
}