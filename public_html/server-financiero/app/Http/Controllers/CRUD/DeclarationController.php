<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Declaration;
use App\DeclarationItemValue;
use App\StateDeclaration;
use App\State;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeclarationController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Declaration::get(),200);
       } else {
          $declaration = Declaration::findOrFail($id);
          $attach = [];
          $declaration_item_values_on_declaration = $declaration->DeclarationItemValues()->get();
          array_push($attach, ["declaration_item_values_on_declaration"=>$declaration_item_values_on_declaration]);
          return response()->json(["Declaration"=>$declaration, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Declaration::paginate($size),200);
    }

    function register_data(Request $data) {
      $result = $data->json()->all();
      if($result['id']==0){
         DB::beginTransaction();
         $declaration = new Declaration();
         $lastDeclaration = Declaration::orderBy('id')->get()->last();
         if($lastDeclaration) {
            $declaration->id = $lastDeclaration->id + 1;
         } else {
            $declaration->id = 1;
         }
         $declaration->establishment_id = $result['establishment_id'];
         $declaration->declaration_date = date("Y-m-d H:i:s");
         $declaration->year = $result['year'];
         $declaration->save();
         $declaration_item_values_on_declaration = $result['declaration_item_values_on_declaration'];
         foreach( $declaration_item_values_on_declaration as $declaration_item_value) {
            $declarationitemvalue = new DeclarationItemValue();
            $lastDeclarationItemValue = DeclarationItemValue::orderBy('id')->get()->last();
            if($lastDeclarationItemValue) {
               $declarationitemvalue->id = $lastDeclarationItemValue->id + 1;
            } else {
               $declarationitemvalue->id = 1;
            }
            $declarationitemvalue->value = $declaration_item_value['value'];
            $declarationitemvalue->declaration_item_id = $declaration_item_value['declaration_item_id'];
            $declarationitemvalue->save();
            $declaration->DeclarationItemValues()->attach($declarationitemvalue->id);
         }
         $statedeclaration = new StateDeclaration();
         $lastStateDeclaration = StateDeclaration::orderBy('id')->get()->last();
         if($lastStateDeclaration) {
            $statedeclaration->id = $lastStateDeclaration->id + 1;
         } else {
            $statedeclaration->id = 1;
         }
         $statedeclaration->justification = 'Declaración Emitida en la Fecha: ' . date('l jS \of F Y h:i:s A');;
         $statedeclaration->moment = date("Y-m-d H:i:s");
         $statedeclaration->declaration_id = $declaration->id;
         $statedeclaration->state_id = 1;
         $statedeclaration->save();
         DB::commit();
         return response()->json($declaration,200);
      } else {
          DB::beginTransaction();
          $declaration = Declaration::where('id',$result['id'])->update([
             'establishment_id'=>$result['establishment_id'],
             'declaration_date'=>date("Y-m-d H:i:s"),
             'year'=>$result['year'],
          ]);
          $declaration = Declaration::where('id',$result['id'])->first();
          $declaration_item_values_on_declaration = $result['declaration_item_values_on_declaration'];
          $declaration_item_values_on_declaration_old = $declaration->DeclarationItemValues()->get();
          foreach( $declaration_item_values_on_declaration_old as $declaration_item_value_old ) {
            DeclarationItemValue::destroy($declaration_item_value_old->id);
          }
          foreach( $declaration_item_values_on_declaration as $declaration_item_value) {
            $declarationitemvalue = new DeclarationItemValue();
            $lastDeclarationItemValue = DeclarationItemValue::orderBy('id')->get()->last();
            if($lastDeclarationItemValue) {
               $declarationitemvalue->id = $lastDeclarationItemValue->id + 1;
            } else {
               $declarationitemvalue->id = 1;
            }
            $declarationitemvalue->value = $declaration_item_value['value'];
            $declarationitemvalue->declaration_item_id = $declaration_item_value['declaration_item_id'];
            $declarationitemvalue->save();
            $declaration->DeclarationItemValues()->attach($declarationitemvalue->id);
          }
          $statedeclaration = new StateDeclaration();
          $lastStateDeclaration = StateDeclaration::orderBy('id')->get()->last();
          if($lastStateDeclaration) {
            $statedeclaration->id = $lastStateDeclaration->id + 1;
          } else {
            $statedeclaration->id = 1;
          }
          $statedeclaration->justification = 'Declaración Actualizada en la Fecha: ' . date('l jS \of F Y h:i:s A');;
          $statedeclaration->moment = date("Y-m-d H:i:s");
          $statedeclaration->declaration_id = $declaration->id;
          $statedeclaration->state_id = 1;
          $statedeclaration->save();
          DB::commit();
          return response()->json($declaration,200);;
      }
    }

    function by_establishment(Request $data) {
      $declarations = Declaration::where('establishment_id', $data['id'])->orderBy('declaration_date')->get();
      $toReturn = [];
      foreach( $declarations as $declaration) {
         $statusDeclaration = StateDeclaration::where('declaration_id',$declaration->id)->orderBy('created_at', 'DESC')->first();
         $state = State::where('id',$statusDeclaration->state_id)->first();
         $status_name = $state->name;
         $declaration_item_values_on_declaration = $declaration->DeclarationItemValues()->get();
         array_push($toReturn, [
         'id'=>$declaration->id,
         'establishment_id'=>$declaration->establishment_id,
         'declaration_date'=>$declaration->declaration_date,
         'year'=>$declaration->year,
         'declaration_item_values_on_declaration'=>$declaration_item_values_on_declaration,
         'status'=>$statusDeclaration,
         'status_name'=>$status_name]);
      }
      return $toReturn;
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $declaration = new Declaration();
          $lastDeclaration = Declaration::orderBy('id')->get()->last();
          if($lastDeclaration) {
             $declaration->id = $lastDeclaration->id + 1;
          } else {
             $declaration->id = 1;
          }
          $declaration->establishment_id = $result['establishment_id'];
          $declaration->declaration_date = $result['declaration_date'];
          $declaration->year = $result['year'];
          $declaration->max_date_to_pay = $result['max_date_to_pay'];
          $declaration->save();
          $declaration_item_values_on_declaration = $result['declaration_item_values_on_declaration'];
          foreach( $declaration_item_values_on_declaration as $declaration_item_value) {
             $declaration->DeclarationItemValues()->attach($declaration_item_value['id']);
          }
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($declaration,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $declaration = Declaration::where('id',$result['id'])->update([
             'establishment_id'=>$result['establishment_id'],
             'declaration_date'=>$result['declaration_date'],
             'year'=>$result['year'],
             'max_date_to_pay'=>$result['max_date_to_pay'],
          ]);
          $declaration = Declaration::where('id',$result['id'])->first();
          $declaration_item_values_on_declaration = $result['declaration_item_values_on_declaration'];
          $declaration_item_values_on_declaration_old = $declaration->DeclarationItemValues()->get();
          foreach( $declaration_item_values_on_declaration_old as $declaration_item_value_old ) {
             $delete = true;
             foreach( $declaration_item_values_on_declaration as $declaration_item_value ) {
                if ( $declaration_item_value_old->id === $declaration_item_value['id'] ) {
                   $delete = false;
                }
             }
             if ( $delete ) {
                $declaration->DeclarationItemValues()->detach($declaration_item_value_old->id);
             }
          }
          foreach( $declaration_item_values_on_declaration as $declaration_item_value ) {
             $add = true;
             foreach( $declaration_item_values_on_declaration_old as $declaration_item_value_old) {
                if ( $declaration_item_value_old->id === $declaration_item_value['id'] ) {
                   $add = false;
                }
             }
             if ( $add ) {
                $declaration->DeclarationItemValues()->attach($declaration_item_value['id']);
             }
          }
          $declaration = Declaration::where('id',$result['id'])->first();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($declaration,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Declaration::destroy($id);
    }

    function backup(Request $data)
    {
       $declarations = Declaration::get();
       $toReturn = [];
       foreach( $declarations as $declaration) {
          $attach = [];
          $declaration_item_values_on_declaration = $declaration->DeclarationItemValues()->get();
          array_push($attach, ["declaration_item_values_on_declaration"=>$declaration_item_values_on_declaration]);
          array_push($toReturn, ["Declaration"=>$declaration, "attach"=>$attach]);
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
         $result = $row['Declaration'];
         $exist = Declaration::where('id',$result['id'])->first();
         if ($exist) {
           Declaration::where('id', $result['id'])->update([
             'establishment_id'=>$result['establishment_id'],
             'declaration_date'=>$result['declaration_date'],
             'year'=>$result['year'],
             'max_date_to_pay'=>$result['max_date_to_pay'],
           ]);
         } else {
          $declaration = new Declaration();
          $declaration->id = $result['id'];
          $declaration->establishment_id = $result['establishment_id'];
          $declaration->declaration_date = $result['declaration_date'];
          $declaration->year = $result['year'];
          $declaration->max_date_to_pay = $result['max_date_to_pay'];
          $declaration->save();
         }
         $declaration = Declaration::where('id',$result['id'])->first();
         $declaration_item_values_on_declaration = [];
         foreach($row['attach'] as $attach){
            $declaration_item_values_on_declaration = $attach['declaration_item_values_on_declaration'];
         }
         $declaration_item_values_on_declaration_old = $declaration->DeclarationItemValues()->get();
         foreach( $declaration_item_values_on_declaration_old as $declaration_item_value_old ) {
            $delete = true;
            foreach( $declaration_item_values_on_declaration as $declaration_item_value ) {
               if ( $declaration_item_value_old->id === $declaration_item_value['id'] ) {
                  $delete = false;
               }
            }
            if ( $delete ) {
               $declaration->DeclarationItemValues()->detach($declaration_item_value_old->id);
            }
         }
         foreach( $declaration_item_values_on_declaration as $declaration_item_value ) {
            $add = true;
            foreach( $declaration_item_values_on_declaration_old as $declaration_item_value_old) {
               if ( $declaration_item_value_old->id === $declaration_item_value['id'] ) {
                  $add = false;
               }
            }
            if ( $add ) {
               $declaration->DeclarationItemValues()->attach($declaration_item_value['id']);
            }
         }
         $declaration = Declaration::where('id',$result['id'])->first();
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}
