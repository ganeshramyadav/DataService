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
    public function getObject(Request $request, $tableName = null, $idOrKey = null, $select = null, $where = null, $orderBy = null){
      
        $select = $request->input('select');
        $where = $request->input('where');
        $orderBy = $request->input('orderBy');

        try { 
            if(empty($tableName)){
                return "Object not found!";
            }
            $result = DbUtils::generateQuery($tableName,$idOrKey,$select,$where,$orderBy);
            return response()->json(['data'=>$result])->setStatusCode(200);

        } catch(Exception $ex){
            throw $ex;
        }
    }
}


