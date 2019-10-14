<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Ruc;
use App\User;
use App\PersonRepresentative;
use App\PersonRepresentativeAttachment;
use App\GroupGiven;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RucController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Ruc::get(),200);
       } else {
          $ruc = Ruc::findOrFail($id);
          $attach = [];
          return response()->json(["Ruc"=>$ruc, "attach"=>$attach],200);
       }
    }

    function get_by_ruc_number(Request $data) {
       $number = $data['number'];
       return response()->json(Ruc::where('number', $number)->first(),200);
    }
    
    function filtered(Request $data) {
      $number = $data['number'];
      $ruc = Ruc::where('number',$number)->first();
      if(!$ruc){
         return response()->json("ruc no encontrado",200);
      }
      $attach = [];
      $groupGiven = GroupGiven::where('ruc_id',$ruc->id)->first();
      if(!$groupGiven){
         $groupGiven = 0;
      }
      $representativePerson = null;
      if($groupGiven){
         $representativePerson = PersonRepresentative::where('id',$groupGiven->person_representative_id)->first();
      }
      if($representativePerson == null){
         $representativePerson = 0;
      }
      $token = $data->header('api_token');
      $user = json_decode($this->httpGet(env('API_AUTH').'user/?id='.$ruc->contact_user_id, null, null, $token));
      return response()->json(["Ruc"=>$ruc, "attach"=>$attach, 'contact_user'=>$user, 'group_given'=>$groupGiven, 'person_representative'=>$representativePerson],200);
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Ruc::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $ruc = new Ruc();
          $lastRuc = Ruc::orderBy('id')->get()->last();
          if($lastRuc) {
             $ruc->id = $lastRuc->id + 1;
          } else {
             $ruc->id = 1;
          }
          $ruc->number = $result['number'];
          $ruc->owner_name = $result['owner_name'];
          $ruc->baised_accounting = $result['baised_accounting'];
          $ruc->contact_user_id = $result['contact_user_id'];
          $ruc->tax_payer_type_id = $result['tax_payer_type_id'];
          $ruc->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($ruc,200);
    }

    function get_id_contact_ruc(Request $data) {
      $toReturn  = [];
      $id = $data['id'];
      $ruc = Ruc::where('contact_user_id', $id)->first();
      if($ruc){
         return response()->json($ruc->contact_user_id,200);
      }
      return response()->json(0,200);
    }

    function register_ruc(Request $data)
    {
       $result = $data->json()->all();
       $contact_user = $result['contact_user'];
       $token = $data->header('api_token');
       $representativeLegal_id = 0;
       $previewPersonRepresentativeAttachment_id = 0;
       if ($result['tax_payer_type_id'] > 1) {
         $representativeLegal = $result['person_representative'];
         $previewRepresentativeLegal =  PersonRepresentative::where('identification', $representativeLegal['identification'])->first();
         if(!$previewRepresentativeLegal){
            DB::beginTransaction();
            $personrepresentative = new PersonRepresentative();
            $lastPersonRepresentative = PersonRepresentative::orderBy('id')->get()->last();
            if($lastPersonRepresentative) {
               $personrepresentative->id = $lastPersonRepresentative->id + 1;
            } else {
               $personrepresentative->id = 1;
            }
            $personrepresentative->identification = $representativeLegal['identification'];
            $personrepresentative->save();
            $representativeLegal_id = $personrepresentative->id;
            DB::commit();
         } else {
            $representativeLegal_id = $previewRepresentativeLegal->id;
         }
         $previewPersonRepresentativeAttachment = PersonRepresentativeAttachment::where('ruc', $result['number'])->first();
         $representativeLegalNombramiento = $result['person_representative_attachment'];
         if (!$previewPersonRepresentativeAttachment) {
            DB::beginTransaction();
            $personrepresentativeattachment = new PersonRepresentativeAttachment();
            $lastPersonRepresentativeAttachment = PersonRepresentativeAttachment::orderBy('id')->get()->last();
            if($lastPersonRepresentativeAttachment) {
               $personrepresentativeattachment->id = $lastPersonRepresentativeAttachment->id + 1;
            } else {
               $personrepresentativeattachment->id = 1;
            }
            $personrepresentativeattachment->person_representative_attachment_file_type = $representativeLegalNombramiento['person_representative_attachment_file_type'];
            $personrepresentativeattachment->person_representative_attachment_file_name = $representativeLegalNombramiento['person_representative_attachment_file_name'];
            $personrepresentativeattachment->person_representative_attachment_file = $representativeLegalNombramiento['person_representative_attachment_file'];
            $personrepresentativeattachment->ruc = $result['number'];
            $personrepresentativeattachment->assignment_date = $representativeLegalNombramiento['assignment_date'];
            $personrepresentativeattachment->person_representative_id = $representativeLegal_id;
            $personrepresentativeattachment->save();
            $previewPersonRepresentativeAttachment_id = $personrepresentativeattachment->id;
            DB::commit();
         } else {
            DB::beginTransaction();
            $personrepresentativeattachment = PersonRepresentativeAttachment::where('id',$previewPersonRepresentativeAttachment->id)->update([
               'person_representative_attachment_file_type'=>$representativeLegalNombramiento['person_representative_attachment_file_type'],
               'person_representative_attachment_file_name'=>$representativeLegalNombramiento['person_representative_attachment_file_name'],
               'person_representative_attachment_file'=>$representativeLegalNombramiento['person_representative_attachment_file'],
               'ruc'=>$result['number'],
               'assignment_date'=>$representativeLegalNombramiento['assignment_date'],
               'person_representative_id'=>$representativeLegal_id,
            ]);
            $previewPersonRepresentativeAttachment_id = $previewPersonRepresentativeAttachment->id;
            DB::commit();
         }
       }
       try{
          DB::beginTransaction();
          $ruc = new Ruc();
          $lastRuc = Ruc::orderBy('id')->get()->last();
          if($lastRuc) {
             $ruc->id = $lastRuc->id + 1;
          } else {
             $ruc->id = 1;
          }
          $ruc->number = $result['number'];
          $ruc->baised_accounting = $result['baised_accounting'];
          $ruc->contact_user_id = $result['contact_user_id'];
          $ruc->owner_name = $result['owner_name'];
          $ruc->tax_payer_type_id = $result['tax_payer_type_id'];
          $ruc->save();
          DB::commit();
          if ($result['tax_payer_type_id'] > 1) {
            $previewGroupGiven = GroupGiven::where('ruc_id', $ruc->id)->first();
            $TipoPersoneriaJuridica = $result['group_given'];
            if(!$previewGroupGiven) {
               DB::beginTransaction();
               $groupgiven = new GroupGiven();
               $lastGroupGiven = GroupGiven::orderBy('id')->get()->last();
               if($lastGroupGiven) {
                  $groupgiven->id = $lastGroupGiven->id + 1;
               } else {
                  $groupgiven->id = 1;
               }
               $groupgiven->register_code = $TipoPersoneriaJuridica['register_code'];
               $groupgiven->ruc_id = $ruc->id;
               $groupgiven->person_representative_id = $representativeLegal_id;
               $groupgiven->group_type_id = $TipoPersoneriaJuridica['group_type_id'];
               $groupgiven->save();
               DB::commit();
            } else {
               DB::beginTransaction();
               $groupgiven = GroupGiven::where('id',$previewGroupGiven->id)->update([
                  'register_code'=>$TipoPersoneriaJuridica['register_code'],
                  'ruc_id'=>$ruc->id,
                  'person_representative_id'=>$representativeLegal_id,
                  'group_type_id'=>$TipoPersoneriaJuridica['group_type_id'],
               ]);
               DB::commit();
            }
         }
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($ruc,200);
    }

    function update_ruc(Request $data)
    {
       $result = $data->json()->all();
       $contact_user = $result['contact_user'];
       $token = $data->header('api_token');
       $representativeLegal_id = 0;
       $previewPersonRepresentativeAttachment_id = 0;
       if ($result['tax_payer_type_id'] > 1) {
         $representativeLegal = $result['person_representative'];
         $previewRepresentativeLegal =  PersonRepresentative::where('identification', $representativeLegal['identification'])->first();
         if(!$previewRepresentativeLegal){
            DB::beginTransaction();
            $personrepresentative = new PersonRepresentative();
            $lastPersonRepresentative = PersonRepresentative::orderBy('id')->get()->last();
            if($lastPersonRepresentative) {
               $personrepresentative->id = $lastPersonRepresentative->id + 1;
            } else {
               $personrepresentative->id = 1;
            }
            $personrepresentative->identification = $representativeLegal['identification'];
            $personrepresentative->save();
            $representativeLegal_id = $personrepresentative->id;
            DB::commit();
         } else {
            $representativeLegal_id = $previewRepresentativeLegal->id;
         }
         $previewPersonRepresentativeAttachment = PersonRepresentativeAttachment::where('ruc', $result['number'])->first();
         $representativeLegalNombramiento = $result['person_representative_attachment'];
         if (!$previewPersonRepresentativeAttachment) {
            DB::beginTransaction();
            $personrepresentativeattachment = new PersonRepresentativeAttachment();
            $lastPersonRepresentativeAttachment = PersonRepresentativeAttachment::orderBy('id')->get()->last();
            if($lastPersonRepresentativeAttachment) {
               $personrepresentativeattachment->id = $lastPersonRepresentativeAttachment->id + 1;
            } else {
               $personrepresentativeattachment->id = 1;
            }
            $personrepresentativeattachment->person_representative_attachment_file_type = $representativeLegalNombramiento['person_representative_attachment_file_type'];
            $personrepresentativeattachment->person_representative_attachment_file_name = $representativeLegalNombramiento['person_representative_attachment_file_name'];
            $personrepresentativeattachment->person_representative_attachment_file = $representativeLegalNombramiento['person_representative_attachment_file'];
            $personrepresentativeattachment->ruc = $result['number'];
            $personrepresentativeattachment->assignment_date = $representativeLegalNombramiento['assignment_date'];
            $personrepresentativeattachment->person_representative_id = $representativeLegal_id;
            $personrepresentativeattachment->save();
            $previewPersonRepresentativeAttachment_id = $personrepresentativeattachment->id;
            DB::commit();
         } else {
            DB::beginTransaction();
            $personrepresentativeattachment = PersonRepresentativeAttachment::where('id',$previewPersonRepresentativeAttachment->id)->update([
               'person_representative_attachment_file_type'=>$representativeLegalNombramiento['person_representative_attachment_file_type'],
               'person_representative_attachment_file_name'=>$representativeLegalNombramiento['person_representative_attachment_file_name'],
               'person_representative_attachment_file'=>$representativeLegalNombramiento['person_representative_attachment_file'],
               'ruc'=>$result['number'],
               'assignment_date'=>$representativeLegalNombramiento['assignment_date'],
               'person_representative_id'=>$representativeLegal_id,
            ]);
            $previewPersonRepresentativeAttachment_id = $previewPersonRepresentativeAttachment->id;
            DB::commit();
         }
      }
      try{
         DB::beginTransaction();
         $ruc = Ruc::where('id',$result['id'])->update([
            'number'=>$result['number'],
            'baised_accounting'=>$result['baised_accounting'],
            'contact_user_id'=>$result['contact_user_id'],
            'owner_name'=>$result['owner_name'],
            'tax_payer_type_id'=>$result['tax_payer_type_id'],
         ]);
         $ruc = Ruc::where('id',$result['id'])->first();
         DB::commit();
         if ($result['tax_payer_type_id'] > 1) {
            $previewGroupGiven = GroupGiven::where('ruc_id', $ruc->id)->first();
            $TipoPersoneriaJuridica = $result['group_given'];
            if(!$previewGroupGiven) {
               DB::beginTransaction();
               $groupgiven = new GroupGiven();
               $lastGroupGiven = GroupGiven::orderBy('id')->get()->last();
               if($lastGroupGiven) {
                  $groupgiven->id = $lastGroupGiven->id + 1;
               } else {
                  $groupgiven->id = 1;
               }
               $groupgiven->register_code = $TipoPersoneriaJuridica['register_code'];
               $groupgiven->ruc_id = $ruc->id;
               $groupgiven->person_representative_id = $representativeLegal_id;
               $groupgiven->group_type_id = $TipoPersoneriaJuridica['group_type_id'];
               $groupgiven->save();
               DB::commit();
            } else {
               DB::beginTransaction();
               $groupgiven = GroupGiven::where('id',$previewGroupGiven->id)->update([
                  'register_code'=>$TipoPersoneriaJuridica['register_code'],
                  'ruc_id'=>$ruc->id,
                  'person_representative_id'=>$representativeLegal_id,
                  'group_type_id'=>$TipoPersoneriaJuridica['group_type_id'],
               ]);
               DB::commit();
            }
         }
      } catch (Exception $e) {
         return $e;
      }
      return response()->json($ruc,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $ruc = Ruc::where('id',$result['id'])->update([
             'number'=>$result['number'],
             'baised_accounting'=>$result['baised_accounting'],
             'contact_user_id'=>$result['contact_user_id'],
             'owner_name'=>$result['owner_name'],
             'tax_payer_type_id'=>$result['tax_payer_type_id'],
          ]);
          $ruc = Ruc::where('id',$result['id'])->first();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($ruc,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Ruc::destroy($id);
    }

    function backup(Request $data)
    {
       $rucs = Ruc::get();
       $toReturn = [];
       foreach( $rucs as $ruc) {
          $attach = [];
          array_push($toReturn, ["Ruc"=>$ruc, "attach"=>$attach]);
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
         $result = $row['Ruc'];
         $exist = Ruc::where('id',$result['id'])->first();
         if ($exist) {
           Ruc::where('id', $result['id'])->update([
             'number'=>$result['number'],
             'baised_accounting'=>$result['baised_accounting'],
             'owner_name'=>$result['owner_name'],
             'contact_user_id'=>$result['contact_user_id'],
             'tax_payer_type_id'=>$result['tax_payer_type_id'],
           ]);
         } else {
          $ruc = new Ruc();
          $ruc->id = $result['id'];
          $ruc->number = $result['number'];
          $ruc->baised_accounting = $result['baised_accounting'];
          $ruc->contact_user_id = $result['contact_user_id'];
          $ruc->owner_name = $result['owner_name'];
          $ruc->tax_payer_type_id = $result['tax_payer_type_id'];
          $ruc->save();
         }
         $ruc = Ruc::where('id',$result['id'])->first();
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
   }

   protected function httpPost($url, $data=NULL, $headers = NULL, $token) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, 1);
      if(!empty($data)){
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      }
      $headersSend = array('Content-Type: application/json');
      array_push($headersSend, 'api_token:'.$token);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headersSend);
      $response = curl_exec($ch);
      if (curl_error($ch)) {
          trigger_error('Curl Error:' . curl_error($ch));
      }
      curl_close($ch);
      return $response;
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

   protected function httpPut($url, $data=NULL, $headers = NULL, $token) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
      if(!empty($data)){
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      }
      $headersSend = array('Content-Type: application/json');
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
