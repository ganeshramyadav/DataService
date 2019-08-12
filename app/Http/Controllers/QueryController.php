<?php
namespace App\Http\Controllers;

use App\Http\Controllers\DataUtilsController;
use Illuminate\Database\QueryException As Exception;
use Illuminate\Http\Request As Request;
use App\Utils\DbUtils ;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Routing\ResponseFactory;

class QueryController extends Controller
{
    public function RecordList(Request $request, $tableName, $select = null, $where = null, $orderBy = null){
      
        $select = $request->input('select');
        $where = $request->input('where');
        $orderBy = $request->input('orderBy');

        try { 
            if(empty($tableName)){
                return "Object not found!";
            }
            $result = DbUtils::generateQuery($tableName,null,$select,$where,$orderBy);
            return response()->json(['object'=>$result])->setStatusCode(200);

        } catch(Exception $ex){
            throw $ex;
        }
    }

    public function GetRecord(Request $request, $tableName, $idOrKey, $select = null, $where = null, $orderBy = null){
        $select = $request->input('select');
        $where = $request->input('where');
        $orderBy = $request->input('orderBy');

        try { 
            if(empty($tableName)){
                return "Object not found!";
            }
            $result = DbUtils::generateQuery($tableName,$idOrKey,$select,$where,$orderBy);
            return response()->json($result)->setStatusCode(200);

        } catch(Exception $ex){
            throw $ex;
        }
    }
	
	public function DeleteRecord(Request $request, $tableName, $idOrKey){
        $where = $request->input('where');
        try { 
            if(empty($tableName)){
                return "Object not found!";
            }
            $result = DbUtils::generateDelete($tableName, $idOrKey, $where);
            return response()->json(['message'=>'Record Deleted Successfully.'])->setStatusCode(200); //delete 204 => no content, 200=>with content;

        } catch(Exception $ex){
            throw $ex;
        }
        return 'true';
    }
	
	// public function BulkInsert($request, $tableName, $idOrKey){}
}


