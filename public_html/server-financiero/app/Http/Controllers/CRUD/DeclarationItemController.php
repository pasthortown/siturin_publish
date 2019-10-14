<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\DeclarationItem;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeclarationItemController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(DeclarationItem::get(),200);
       } else {
          $declarationitem = DeclarationItem::findOrFail($id);
          $attach = [];
          return response()->json(["DeclarationItem"=>$declarationitem, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(DeclarationItem::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $declarationitem = new DeclarationItem();
          $lastDeclarationItem = DeclarationItem::orderBy('id')->get()->last();
          if($lastDeclarationItem) {
             $declarationitem->id = $lastDeclarationItem->id + 1;
          } else {
             $declarationitem->id = 1;
          }
          $declarationitem->name = $result['name'];
          $declarationitem->description = $result['description'];
          $declarationitem->factor = $result['factor'];
          $declarationitem->year = $result['year'];
          $declarationitem->tax_payer_type_id = $result['tax_payer_type_id'];
          $declarationitem->declaration_item_category_id = $result['declaration_item_category_id'];
          $declarationitem->save();
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($declarationitem,200);
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $declarationitem = DeclarationItem::where('id',$result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'factor'=>$result['factor'],
             'year'=>$result['year'],
             'tax_payer_type_id'=>$result['tax_payer_type_id'],
             'declaration_item_category_id'=>$result['declaration_item_category_id'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($declarationitem,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return DeclarationItem::destroy($id);
    }

    function backup(Request $data)
    {
       $declarationitems = DeclarationItem::get();
       $toReturn = [];
       foreach( $declarationitems as $declarationitem) {
          $attach = [];
          array_push($toReturn, ["DeclarationItem"=>$declarationitem, "attach"=>$attach]);
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
         $result = $row['DeclarationItem'];
         $exist = DeclarationItem::where('id',$result['id'])->first();
         if ($exist) {
           DeclarationItem::where('id', $result['id'])->update([
             'name'=>$result['name'],
             'description'=>$result['description'],
             'factor'=>$result['factor'],
             'year'=>$result['year'],
             'tax_payer_type_id'=>$result['tax_payer_type_id'],
             'declaration_item_category_id'=>$result['declaration_item_category_id'],
           ]);
         } else {
          $declarationitem = new DeclarationItem();
          $declarationitem->id = $result['id'];
          $declarationitem->name = $result['name'];
          $declarationitem->description = $result['description'];
          $declarationitem->factor = $result['factor'];
          $declarationitem->year = $result['year'];
          $declarationitem->tax_payer_type_id = $result['tax_payer_type_id'];
          $declarationitem->declaration_item_category_id = $result['declaration_item_category_id'];
          $declarationitem->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}