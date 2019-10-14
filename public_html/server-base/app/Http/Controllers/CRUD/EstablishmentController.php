<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Establishment;
use App\Ruc;
use App\Worker;
use App\EstablishmentCertification;
use App\EstablishmentCertificationAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EstablishmentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Establishment::get(),200);
       } else {
          $establishment = Establishment::findOrFail($id);
          $attach = [];
          $preview_register_codes_on_establishment = $establishment->PreviewRegisterCodes()->get();
          array_push($attach, ["preview_register_codes_on_establishment"=>$preview_register_codes_on_establishment]);
          $languages_on_establishment = $establishment->Languages()->get();
          array_push($attach, ["languages_on_establishment"=>$languages_on_establishment]);
          $workers_on_establishment = $establishment->Workers()->get();
          array_push($attach, ["workers_on_establishment"=>$workers_on_establishment]);
          $establishment_certifications_on_establishment = $establishment->EstablishmentCertifications()->get();
          array_push($attach, ["establishment_certifications_on_establishment"=>$establishment_certifications_on_establishment]);
          return response()->json(["Establishment"=>$establishment, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
    
       return response()->json(Establishment::paginate($size),200);
    }

    function getByRuc(Request $data) {
      $rucNumber = $data['ruc'];
      $size = $data['size'];
      $ruc = Ruc::where('number', $rucNumber)->first();
      return response()->json(Establishment::where('ruc_id', $ruc->id)->paginate($size),200);
    }

    function set_register_date(Request $data) {
      $result = $data->json()->all();
      try{
         DB::beginTransaction();
         $establishment = Establishment::where('id', $result['id'])->update([
            'as_turistic_register_date'=> date("Y-m-d H:i:s"),
         ]);
         DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json($establishment,200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishment = new Establishment();
          $lastEstablishment = Establishment::orderBy('id')->get()->last();
          if($lastEstablishment) {
             $establishment->id = $lastEstablishment->id + 1;
          } else {
             $establishment->id = 1;
          }
          $establishment->ruc_code_id = $result['ruc_code_id'];
          $establishment->commercially_known_name = $result['commercially_known_name'];
          $establishment->address_main_street = $result['address_main_street'];
          $establishment->address_secondary_street = $result['address_secondary_street'];
          $establishment->address_number = $result['address_number'];
          $establishment->address_map_latitude = $result['address_map_latitude'];
          $establishment->address_map_longitude = $result['address_map_longitude'];
          $establishment->url_web = $result['url_web'];
          $establishment->as_turistic_register_date = $result['as_turistic_register_date'];
          $establishment->address_reference = $result['address_reference'];
          $establishment->contact_user_id = $result['contact_user_id'];
          $establishment->ruc_id = $result['ruc_id'];
          $establishment->franchise_chain_name = $result['franchise_chain_name'];
          $establishment->ubication_id = $result['ubication_id'];
          $establishment->establishment_property_type_id = $result['establishment_property_type_id'];
          $establishment->ruc_name_type_id = $result['ruc_name_type_id'];
          $establishment->save();
          $preview_register_codes_on_establishment = $result['preview_register_codes_on_establishment'];
          foreach( $preview_register_codes_on_establishment as $preview_register_code) {
             $establishment->PreviewRegisterCodes()->attach($preview_register_code['id']);
          }
          $languages_on_establishment = $result['languages_on_establishment'];
          foreach( $languages_on_establishment as $language) {
             $establishment->Languages()->attach($language['id']);
          }
          $workers_on_establishment = $result['workers_on_establishment'];
          foreach( $workers_on_establishment as $worker) {
             $establishment->Workers()->attach($worker['id']);
          }
          $establishment_certifications_on_establishment = $result['establishment_certifications_on_establishment'];
          foreach( $establishment_certifications_on_establishment as $establishment_certification) {
             $establishment->EstablishmentCertifications()->attach($establishment_certification['id']);
          }
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishment,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $establishment = Establishment::where('id',$result['id'])->update([
             'ruc_code_id'=>$result['ruc_code_id'],
             'commercially_known_name'=>$result['commercially_known_name'],
             'address_main_street'=>$result['address_main_street'],
             'address_secondary_street'=>$result['address_secondary_street'],
             'address_number'=>$result['address_number'],
             'franchise_chain_name'=>$result['franchise_chain_name'],
             'address_map_latitude'=>$result['address_map_latitude'],
             'address_map_longitude'=>$result['address_map_longitude'],
             'url_web'=>$result['url_web'],
             'as_turistic_register_date'=>$result['as_turistic_register_date'],
             'address_reference'=>$result['address_reference'],
             'contact_user_id'=>$result['contact_user_id'],
             'ruc_id'=>$result['ruc_id'],
             'ubication_id'=>$result['ubication_id'],
             'establishment_property_type_id'=>$result['establishment_property_type_id'],
             'ruc_name_type_id'=>$result['ruc_name_type_id'],
          ]);
          $establishment = Establishment::where('id',$result['id'])->first();
          $preview_register_codes_on_establishment = $result['preview_register_codes_on_establishment'];
          $preview_register_codes_on_establishment_old = $establishment->PreviewRegisterCodes()->get();
          foreach( $preview_register_codes_on_establishment_old as $preview_register_code_old ) {
             $delete = true;
             foreach( $preview_register_codes_on_establishment as $preview_register_code ) {
                if ( $preview_register_code_old->id === $preview_register_code['id'] ) {
                   $delete = false;
                }
             }
             if ( $delete ) {
                $establishment->PreviewRegisterCodes()->detach($preview_register_code_old->id);
             }
          }
          foreach( $preview_register_codes_on_establishment as $preview_register_code ) {
             $add = true;
             foreach( $preview_register_codes_on_establishment_old as $preview_register_code_old) {
                if ( $preview_register_code_old->id === $preview_register_code['id'] ) {
                   $add = false;
                }
             }
             if ( $add ) {
                $establishment->PreviewRegisterCodes()->attach($preview_register_code['id']);
             }
          }
          $establishment = Establishment::where('id',$result['id'])->first();
          $languages_on_establishment = $result['languages_on_establishment'];
          $languages_on_establishment_old = $establishment->Languages()->get();
          foreach( $languages_on_establishment_old as $language_old ) {
             $delete = true;
             foreach( $languages_on_establishment as $language ) {
                if ( $language_old->id === $language['id'] ) {
                   $delete = false;
                }
             }
             if ( $delete ) {
                $establishment->Languages()->detach($language_old->id);
             }
          }
          foreach( $languages_on_establishment as $language ) {
             $add = true;
             foreach( $languages_on_establishment_old as $language_old) {
                if ( $language_old->id === $language['id'] ) {
                   $add = false;
                }
             }
             if ( $add ) {
                $establishment->Languages()->attach($language['id']);
             }
          }
          $establishment = Establishment::where('id',$result['id'])->first();
          $workers_on_establishment = $result['workers_on_establishment'];
          $workers_on_establishment_old = $establishment->Workers()->get();
          foreach( $workers_on_establishment_old as $worker_old ) {
             $delete = true;
             foreach( $workers_on_establishment as $worker ) {
                if ( $worker_old->id === $worker['id'] ) {
                   $delete = false;
                }
             }
             if ( $delete ) {
                $establishment->Workers()->detach($worker_old->id);
             }
          }
          foreach( $workers_on_establishment as $worker ) {
             $add = true;
             foreach( $workers_on_establishment_old as $worker_old) {
                if ( $worker_old->id === $worker['id'] ) {
                   $add = false;
                }
             }
             if ( $add ) {
                $establishment->Workers()->attach($worker['id']);
             }
          }
          $establishment = Establishment::where('id',$result['id'])->first();
          $establishment_certifications_on_establishment = $result['establishment_certifications_on_establishment'];
          $establishment_certifications_on_establishment_old = $establishment->EstablishmentCertifications()->get();
          foreach( $establishment_certifications_on_establishment_old as $establishment_certification_old ) {
             $delete = true;
             foreach( $establishment_certifications_on_establishment as $establishment_certification ) {
                if ( $establishment_certification_old->id === $establishment_certification['id'] ) {
                   $delete = false;
                }
             }
             if ( $delete ) {
                $establishment->EstablishmentCertifications()->detach($establishment_certification_old->id);
             }
          }
          foreach( $establishment_certifications_on_establishment as $establishment_certification ) {
             $add = true;
             foreach( $establishment_certifications_on_establishment_old as $establishment_certification_old) {
                if ( $establishment_certification_old->id === $establishment_certification['id'] ) {
                   $add = false;
                }
             }
             if ( $add ) {
                $establishment->EstablishmentCertifications()->attach($establishment_certification['id']);
             }
          }
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($establishment,200);
    }

    function register_establishment_data(Request $data) {
      $result = $data->json()->all();
      $contact_user = $result['contact_user'];
      $token = $data->header('api_token');
      $respuesta = "0";
      $respuesta = $this->httpPost(env('API_AUTH').'user/register_user_establishment', json_encode($contact_user), null, $token);
      $contact_user_id = $respuesta;
      $ruc = Ruc::where('id', $result['ruc_id'])->first();
      $establishmentPreview = Establishment::where('ruc_id', $result['ruc_id'])->where('ruc_code_id',$result['ruc_code_id'])->first();
      if($establishmentPreview){
         try{
            DB::beginTransaction();
            $result = $data->json()->all();
            $establishment = Establishment::where('id',$result['id'])->update([
               'ruc_code_id'=>$result['ruc_code_id'],
               'commercially_known_name'=>$result['commercially_known_name'],
               'address_main_street'=>$result['address_main_street'],
               'address_secondary_street'=>$result['address_secondary_street'],
               'address_number'=>$result['address_number'],
               'franchise_chain_name'=>$result['franchise_chain_name'],
               'address_map_latitude'=>$result['address_map_latitude'],
               'address_map_longitude'=>$result['address_map_longitude'],
               'url_web'=>$result['url_web'],
               'address_reference'=>$result['address_reference'],
               'contact_user_id'=>$contact_user_id,
               'ruc_id'=>$result['ruc_id'],
               'ubication_id'=>$result['ubication_id'],
               'establishment_property_type_id'=>$result['establishment_property_type_id'],
               'ruc_name_type_id'=>$result['ruc_name_type_id'],
            ]);
            $establishment = Establishment::where('id',$result['id'])->first();
            $establishment_certifications_on_establishment_old = $establishment->EstablishmentCertifications()->get();
            foreach( $establishment_certifications_on_establishment_old as $certification_old ) {
               $establishment->EstablishmentCertifications()->detach($certification_old->id);
               EstablishmentCertification::destroy($certification_old->id);
            }
            $workers_on_establishment_old = $establishment->Workers()->get();
            foreach( $workers_on_establishment_old as $worker_old ) {
               $establishment->Workers()->detach($worker_old->id);
               Worker::destroy($worker_old->id);
            }
            $establishment_certifications_on_establishment = $result['establishment_certifications_on_establishment'];
            foreach( $establishment_certifications_on_establishment as $establishment_certification_on_establishment) {
               $establishment_certification_attachment = $establishment_certification_on_establishment['establishment_certification_attachment'];
               $establishmentcertificationattachment = new EstablishmentCertificationAttachment();
               $lastEstablishmentCertificationAttachment = EstablishmentCertificationAttachment::orderBy('id')->get()->last();
               if($lastEstablishmentCertificationAttachment) {
                  $establishmentcertificationattachment->id = $lastEstablishmentCertificationAttachment->id + 1;
               } else {
                  $establishmentcertificationattachment->id = 1;
               }
               $establishmentcertificationattachment->establishment_certification_attachment_file_type = $establishment_certification_attachment['establishment_certification_attachment_file_type'];
               $establishmentcertificationattachment->establishment_certification_attachment_file_name = $establishment_certification_attachment['establishment_certification_attachment_file_name'];
               $establishmentcertificationattachment->establishment_certification_attachment_file = $establishment_certification_attachment['establishment_certification_attachment_file'];
               $establishmentcertificationattachment->save();
               $establishmentcertification = new EstablishmentCertification();
               $lastEstablishmentCertification = EstablishmentCertification::orderBy('id')->get()->last();
               if($lastEstablishmentCertification) {
                  $establishmentcertification->id = $lastEstablishmentCertification->id + 1;
               } else {
                  $establishmentcertification->id = 1;
               }
               $establishmentcertification->establishment_certification_type_id = $establishment_certification_on_establishment['establishment_certification_type_id'];
               $establishmentcertification->establishment_certification_attachment_id = $establishmentcertificationattachment->id;
               $establishmentcertification->save();
               $establishment->EstablishmentCertifications()->attach($establishmentcertification->id);
            }
            $workers_on_establishment = $result['workers_on_establishment'];
            foreach( $workers_on_establishment as $worker_on_establishment) {
               $worker = new Worker();
               $lastWorker = Worker::orderBy('id')->get()->last();
               if($lastWorker) {
                  $worker->id = $lastWorker->id + 1;
               } else {
                  $worker->id = 1;
               }
               $worker->count = $worker_on_establishment['count'];
               $worker->gender_id = $worker_on_establishment['gender_id'];
               $worker->worker_group_id = $worker_on_establishment['worker_group_id'];
               $worker->save();
               $establishment->Workers()->attach($worker->id);
            }
            DB::commit();
            return response()->json($establishment,200);
         } catch (Exception $e) {
            return response()->json($e,400);
         }
      } else {
         try{
            DB::beginTransaction();
            $establishment = new Establishment();
            $lastEstablishment = Establishment::orderBy('id')->get()->last();
            if($lastEstablishment) {
               $establishment->id = $lastEstablishment->id + 1;
            } else {
               $establishment->id = 1;
            }
            $establishment->ruc_code_id = $result['ruc_code_id'];
            $establishment->commercially_known_name = $result['commercially_known_name'];
            $establishment->address_main_street = $result['address_main_street'];
            $establishment->address_secondary_street = $result['address_secondary_street'];
            $establishment->address_number = $result['address_number'];
            $establishment->franchise_chain_name = $result['franchise_chain_name'];
            $establishment->address_map_latitude = $result['address_map_latitude'];
            $establishment->address_map_longitude = $result['address_map_longitude'];
            $establishment->url_web = $result['url_web'];
            $establishment->address_reference = $result['address_reference'];
            $establishment->contact_user_id = $contact_user_id;
            $establishment->ruc_id = $result['ruc_id'];
            $establishment->ubication_id = $result['ubication_id'];
            $establishment->establishment_property_type_id = $result['establishment_property_type_id'];
            $establishment->ruc_name_type_id = $result['ruc_name_type_id'];
            $establishment->save();
            $establishment_certifications_on_establishment = $result['establishment_certifications_on_establishment'];
            foreach( $establishment_certifications_on_establishment as $establishment_certification_on_establishment) {
               $establishment_certification_attachment = $establishment_certification_on_establishment['establishment_certification_attachment'];
               $establishmentcertificationattachment = new EstablishmentCertificationAttachment();
               $lastEstablishmentCertificationAttachment = EstablishmentCertificationAttachment::orderBy('id')->get()->last();
               if($lastEstablishmentCertificationAttachment) {
                  $establishmentcertificationattachment->id = $lastEstablishmentCertificationAttachment->id + 1;
               } else {
                  $establishmentcertificationattachment->id = 1;
               }
               $establishmentcertificationattachment->establishment_certification_attachment_file_type = $establishment_certification_attachment['establishment_certification_attachment_file_type'];
               $establishmentcertificationattachment->establishment_certification_attachment_file_name = $establishment_certification_attachment['establishment_certification_attachment_file_name'];
               $establishmentcertificationattachment->establishment_certification_attachment_file = $establishment_certification_attachment['establishment_certification_attachment_file'];
               $establishmentcertificationattachment->save();
               $establishmentcertification = new EstablishmentCertification();
               $lastEstablishmentCertification = EstablishmentCertification::orderBy('id')->get()->last();
               if($lastEstablishmentCertification) {
                  $establishmentcertification->id = $lastEstablishmentCertification->id + 1;
               } else {
                  $establishmentcertification->id = 1;
               }
               $establishmentcertification->establishment_certification_type_id = $establishment_certification_on_establishment['establishment_certification_type_id'];
               $establishmentcertification->establishment_certification_attachment_id = $establishmentcertificationattachment->id;
               $establishmentcertification->save();
               $establishment->EstablishmentCertifications()->attach($establishmentcertification->id);
            }
            $workers_on_establishment = $result['workers_on_establishment'];
            foreach( $workers_on_establishment as $worker_on_establishment) {
               $worker = new Worker();
               $lastWorker = Worker::orderBy('id')->get()->last();
               if($lastWorker) {
                  $worker->id = $lastWorker->id + 1;
               } else {
                  $worker->id = 1;
               }
               $worker->count = $worker_on_establishment['count'];
               $worker->gender_id = $worker_on_establishment['gender_id'];
               $worker->worker_group_id = $worker_on_establishment['worker_group_id'];
               $worker->save();
               $establishment->Workers()->attach($worker->id);
            }
            DB::commit();
            return response()->json($establishment,200);
         } catch (Exception $e) {
            return $e;
         }
      }
    }

    function filtered(Request $data) {
      $id = $data['id'];
      $token = $data->header('api_token');
      $establishment = Establishment::where('id', $id)->first();
      $contact_user = json_decode($this->httpGet(env('API_AUTH').'user/?id='.$establishment->contact_user_id, null, null, $token));
      $languages_on_establishment = $establishment->Languages()->get();
      $workers_on_establishment = $establishment->Workers()->get();
      $establishment_certifications_on_establishment = $establishment->EstablishmentCertifications()->get();
      return response()->json(["establishment"=>$establishment, "contact_user"=>$contact_user, "languages_on_establishment"=>$languages_on_establishment, "workers_on_establishment"=>$workers_on_establishment, "establishment_certifications_on_establishment"=>$establishment_certifications_on_establishment],200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Establishment::destroy($id);
    }

    function backup(Request $data)
    {
       $establishments = Establishment::get();
       $toReturn = [];
       foreach( $establishments as $establishment) {
          $attach = [];
          $preview_register_codes_on_establishment = $establishment->PreviewRegisterCodes()->get();
          array_push($attach, ["preview_register_codes_on_establishment"=>$preview_register_codes_on_establishment]);
          $languages_on_establishment = $establishment->Languages()->get();
          array_push($attach, ["languages_on_establishment"=>$languages_on_establishment]);
          $workers_on_establishment = $establishment->Workers()->get();
          array_push($attach, ["workers_on_establishment"=>$workers_on_establishment]);
          $establishment_certifications_on_establishment = $establishment->EstablishmentCertifications()->get();
          array_push($attach, ["establishment_certifications_on_establishment"=>$establishment_certifications_on_establishment]);
          array_push($toReturn, ["Establishment"=>$establishment, "attach"=>$attach]);
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
         $result = $row['Establishment'];
         $exist = Establishment::where('id',$result['id'])->first();
         if ($exist) {
           Establishment::where('id', $result['id'])->update([
             'ruc_code_id'=>$result['ruc_code_id'],
             'commercially_known_name'=>$result['commercially_known_name'],
             'address_main_street'=>$result['address_main_street'],
             'address_secondary_street'=>$result['address_secondary_street'],
             'address_number'=>$result['address_number'],
             'franchise_chain_name'=>$result['franchise_chain_name'],             
             'address_map_latitude'=>$result['address_map_latitude'],
             'address_map_longitude'=>$result['address_map_longitude'],
             'url_web'=>$result['url_web'],
             'as_turistic_register_date'=>$result['as_turistic_register_date'],
             'address_reference'=>$result['address_reference'],
             'contact_user_id'=>$result['contact_user_id'],
             'ruc_id'=>$result['ruc_id'],
             'ubication_id'=>$result['ubication_id'],
             'establishment_property_type_id'=>$result['establishment_property_type_id'],
             'ruc_name_type_id'=>$result['ruc_name_type_id'],
           ]);
         } else {
          $establishment = new Establishment();
          $establishment->id = $result['id'];
          $establishment->ruc_code_id = $result['ruc_code_id'];
          $establishment->commercially_known_name = $result['commercially_known_name'];
          $establishment->address_main_street = $result['address_main_street'];
          $establishment->address_secondary_street = $result['address_secondary_street'];
          $establishment->address_number = $result['address_number'];
          $establishment->franchise_chain_name = $result['franchise_chain_name'];
          $establishment->address_map_latitude = $result['address_map_latitude'];
          $establishment->address_map_longitude = $result['address_map_longitude'];
          $establishment->url_web = $result['url_web'];
          $establishment->as_turistic_register_date = $result['as_turistic_register_date'];
          $establishment->address_reference = $result['address_reference'];
          $establishment->contact_user_id = $result['contact_user_id'];
          $establishment->ruc_id = $result['ruc_id'];
          $establishment->ubication_id = $result['ubication_id'];
          $establishment->establishment_property_type_id = $result['establishment_property_type_id'];
          $establishment->ruc_name_type_id = $result['ruc_name_type_id'];
          $establishment->save();
         }
         $establishment = Establishment::where('id',$result['id'])->first();
         $preview_register_codes_on_establishment = [];
         foreach($row['attach'] as $attach){
            $preview_register_codes_on_establishment = $attach['preview_register_codes_on_establishment'];
         }
         $preview_register_codes_on_establishment_old = $establishment->PreviewRegisterCodes()->get();
         foreach( $preview_register_codes_on_establishment_old as $preview_register_code_old ) {
            $delete = true;
            foreach( $preview_register_codes_on_establishment as $preview_register_code ) {
               if ( $preview_register_code_old->id === $preview_register_code['id'] ) {
                  $delete = false;
               }
            }
            if ( $delete ) {
               $establishment->PreviewRegisterCodes()->detach($preview_register_code_old->id);
            }
         }
         foreach( $preview_register_codes_on_establishment as $preview_register_code ) {
            $add = true;
            foreach( $preview_register_codes_on_establishment_old as $preview_register_code_old) {
               if ( $preview_register_code_old->id === $preview_register_code['id'] ) {
                  $add = false;
               }
            }
            if ( $add ) {
               $establishment->PreviewRegisterCodes()->attach($preview_register_code['id']);
            }
         }
         $establishment = Establishment::where('id',$result['id'])->first();
         $languages_on_establishment = [];
         foreach($row['attach'] as $attach){
            $languages_on_establishment = $attach['languages_on_establishment'];
         }
         $languages_on_establishment_old = $establishment->Languages()->get();
         foreach( $languages_on_establishment_old as $language_old ) {
            $delete = true;
            foreach( $languages_on_establishment as $language ) {
               if ( $language_old->id === $language['id'] ) {
                  $delete = false;
               }
            }
            if ( $delete ) {
               $establishment->Languages()->detach($language_old->id);
            }
         }
         foreach( $languages_on_establishment as $language ) {
            $add = true;
            foreach( $languages_on_establishment_old as $language_old) {
               if ( $language_old->id === $language['id'] ) {
                  $add = false;
               }
            }
            if ( $add ) {
               $establishment->Languages()->attach($language['id']);
            }
         }
         $establishment = Establishment::where('id',$result['id'])->first();
         $workers_on_establishment = [];
         foreach($row['attach'] as $attach){
            $workers_on_establishment = $attach['workers_on_establishment'];
         }
         $workers_on_establishment_old = $establishment->Workers()->get();
         foreach( $workers_on_establishment_old as $worker_old ) {
            $delete = true;
            foreach( $workers_on_establishment as $worker ) {
               if ( $worker_old->id === $worker['id'] ) {
                  $delete = false;
               }
            }
            if ( $delete ) {
               $establishment->Workers()->detach($worker_old->id);
            }
         }
         foreach( $workers_on_establishment as $worker ) {
            $add = true;
            foreach( $workers_on_establishment_old as $worker_old) {
               if ( $worker_old->id === $worker['id'] ) {
                  $add = false;
               }
            }
            if ( $add ) {
               $establishment->Workers()->attach($worker['id']);
            }
         }
         $establishment = Establishment::where('id',$result['id'])->first();
         $establishment_certifications_on_establishment = [];
         foreach($row['attach'] as $attach){
            $establishment_certifications_on_establishment = $attach['establishment_certifications_on_establishment'];
         }
         $establishment_certifications_on_establishment_old = $establishment->EstablishmentCertifications()->get();
         foreach( $establishment_certifications_on_establishment_old as $establishment_certification_old ) {
            $delete = true;
            foreach( $establishment_certifications_on_establishment as $establishment_certification ) {
               if ( $establishment_certification_old->id === $establishment_certification['id'] ) {
                  $delete = false;
               }
            }
            if ( $delete ) {
               $establishment->EstablishmentCertifications()->detach($establishment_certification_old->id);
            }
         }
         foreach( $establishment_certifications_on_establishment as $establishment_certification ) {
            $add = true;
            foreach( $establishment_certifications_on_establishment_old as $establishment_certification_old) {
               if ( $establishment_certification_old->id === $establishment_certification['id'] ) {
                  $add = false;
               }
            }
            if ( $add ) {
               $establishment->EstablishmentCertifications()->attach($establishment_certification['id']);
            }
         }
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
