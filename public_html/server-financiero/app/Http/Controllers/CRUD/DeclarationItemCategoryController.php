<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\DeclarationItemCategory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeclarationItemCategoryController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(DeclarationItemCategory::get(),200);
       } else {
          $declarationitemcategory = DeclarationItemCategory::findOrFail($id);
          $attach = [];
          return response()->json(["DeclarationItemCategory"=>$declarationitemcategory, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(DeclarationItemCategory::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $declarationitemcategory = new DeclarationItemCategory();
          $lastDeclarationItemCategory = DeclarationItemCategory::orderBy('id')->get()->last();
          if($lastDeclarationItemCategory) {
             $declarationitemcategory->id = $lastDeclarationItemCategory->id + 1;
          } else {
             $declarationitemcategory->id = 1;
          }
          $declarationitemcategory->name = $result['name'];
          $declarationitemcategory->description = $result['description'];
          $declarationitemcategory->year = $result['year'];
          $declarationitemcategory->tax_payer_type_id = $result['tax_payer_type_id'];
          $declarationitemcategory->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($declarationitemcategory,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $declarationitemcategory = DeclarationItemCategory::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'year'=>$result['year'],
             'tax_payer_type_id'=>$result['tax_payer_type_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($declarationitemcategory,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return DeclarationItemCategory::destroy($id);
    }

    function backup(Request $data)
    {
       $declarationitemcategories = DeclarationItemCategory::get();
       $toReturn = [];
       foreach( $declarationitemcategories as $declarationitemcategory) {
          $attach = [];
          array_push($toReturn, ["DeclarationItemCategory"=>$declarationitemcategory, "attach"=>$attach]);
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
         $result = $row['DeclarationItemCategory'];
         $exist = DeclarationItemCategory::where('id',$result['id'])->first();
         if ($exist) {
           DeclarationItemCategory::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'year'=>$result['year'],
             'tax_payer_type_id'=>$result['tax_payer_type_id'],
           ]);
         } else {
          $declarationitemcategory = new DeclarationItemCategory();
          $declarationitemcategory->id = $result['id'];
          $declarationitemcategory->name = $result['name'];
          $declarationitemcategory->description = $result['description'];
          $declarationitemcategory->year = $result['year'];
          $declarationitemcategory->tax_payer_type_id = $result['tax_payer_type_id'];
          $declarationitemcategory->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}