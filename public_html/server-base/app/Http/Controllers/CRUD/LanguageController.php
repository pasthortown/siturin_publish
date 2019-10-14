<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Language;
use App\Establishment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LanguageController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Language::orderBy("name")->get(),200);
       } else {
          $language = Language::findOrFail($id);
          $attach = [];
          return response()->json(["Language"=>$language, "attach"=>$attach],200);
       }
    }

    function save_languajes(Request $data) {
      $result = $data->json()->all();
      $establishment = Establishment::where('id',$result['establishment_id'])->first();
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
      return 1;
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Language::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $language = new Language();
          $lastLanguage = Language::orderBy('id')->get()->last();
          if($lastLanguage) {
             $language->id = $lastLanguage->id + 1;
          } else {
             $language->id = 1;
          }
          $language->name = $result['name'];
          $language->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($language,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $language = Language::where('id',$result['id'])->update([
             'name'=>$result['name'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($language,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Language::destroy($id);
    }

    function backup(Request $data)
    {
       $languages = Language::get();
       $toReturn = [];
       foreach( $languages as $language) {
          $attach = [];
          array_push($toReturn, ["Language"=>$language, "attach"=>$attach]);
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
         $result = $row['Language'];
         $exist = Language::where('id',$result['id'])->first();
         if ($exist) {
           Language::where('id', $result['id'])->update([
             'name'=>$result['name'],
           ]);
         } else {
          $language = new Language();
          $language->id = $result['id'];
          $language->name = $result['name'];
          $language->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}