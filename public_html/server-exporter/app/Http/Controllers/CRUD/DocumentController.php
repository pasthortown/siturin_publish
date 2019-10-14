<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Document;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DocumentController extends Controller
{
    function get(Request $data)
    {
       $id = $data['id'];
       if ($id == null) {
          return response()->json(Document::get(),200);
       } else {
          $document = Document::findOrFail($id);
          $attach = [];
          return response()->json(["Document"=>$document, "attach"=>$attach],200);
       }
    }

    function paginate(Request $data)
    {
       $size = $data['size'];
       return response()->json(Document::paginate($size),200);
    }

    function post(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $preview_document = Document::where('code', $result['code'])->first();
          if ($preview_document) {
            $document = Document::where('code', $result['code'])->update([
               'params'=>$result['params'],
               'code'=>$result['code'],
               'procedure_id'=>$result['procedure_id'],
               'activity'=>$result['activity'],
               'zonal'=>$result['zonal'],
               'document_type'=>$result['document_type'],
               'user'=>$result['user'],
            ]);
          } else {
            $document = new Document();
            $lastDocument = Document::orderBy('id')->get()->last();
            if($lastDocument) {
               $document->id = $lastDocument->id + 1;
            } else {
               $document->id = 1;
            }
            $document->params = $result['params'];
            $document->code = $result['code'];
            $document->procedure_id = $result['procedure_id'];
            $document->activity = $result['activity'];
            $document->zonal = $result['zonal'];
            $document->document_type = $result['document_type'];
            $document->user = $result['user'];
            $document->save();
          }
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($document,200);
    }

    function get_doc_id(Request $data) {
      $result = $data->json()->all();
      $preview_document = Document::where('code', $result['code'])->first();
      if ($preview_document) {
         return $preview_document->id;
      }
      $lastDocument = Document::orderBy('id')->get()->last();
      if($lastDocument) {
         return $lastDocument->id + 1;
      } else {
         return 1;
      }
    }

    function put(Request $data)
    {
       try{
          DB::beginTransaction();
          $result = $data->json()->all();
          $document = Document::where('id',$result['id'])->update([
             'params'=>$result['params'],
             'code'=>$result['code'],
             'procedure_id'=>$result['procedure_id'],
             'activity'=>$result['activity'],
             'zonal'=>$result['zonal'],
             'document_type'=>$result['document_type'],
             'user'=>$result['user'],
          ]);
          DB::commit();
       } catch (Exception $e) {
          return response()->json($e,400);
       }
       return response()->json($document,200);
    }

    function delete(Request $data)
    {
       $id = $data['id'];
       return Document::destroy($id);
    }

    function backup(Request $data)
    {
       $documents = Document::get();
       $toReturn = [];
       foreach( $documents as $document) {
          $attach = [];
          array_push($toReturn, ["Document"=>$document, "attach"=>$attach]);
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
         $result = $row['Document'];
         $exist = Document::where('id',$result['id'])->first();
         if ($exist) {
           Document::where('id', $result['id'])->update([
             'params'=>$result['params'],
             'code'=>$result['code'],
             'procedure_id'=>$result['procedure_id'],
             'activity'=>$result['activity'],
             'zonal'=>$result['zonal'],
             'document_type'=>$result['document_type'],
             'user'=>$result['user'],
           ]);
         } else {
          $document = new Document();
          $document->id = $result['id'];
          $document->params = $result['params'];
          $document->code = $result['code'];
          $document->procedure_id = $result['procedure_id'];
          $document->activity = $result['activity'];
          $document->zonal = $result['zonal'];
          $document->document_type = $result['document_type'];
          $document->user = $result['user'];
          $document->save();
         }
       }
       DB::commit();
      } catch (Exception $e) {
         return response()->json($e,400);
      }
      return response()->json('Task Complete',200);
    }
}