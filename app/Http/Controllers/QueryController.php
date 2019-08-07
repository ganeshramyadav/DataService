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

        $identityUrl = env('IDENTITY_URL');
        $authorization = $request->header('authorization');

        $result = QueryController::callIdentityCurlService($identityUrl, $authorization);

        if($result->status() == 401){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

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

    public function callIdentityCurlService($identityUrl, $authorization){
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $identityUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: '.$authorization;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        if($httpcode === 401){
            return response()->json(['error' => 'Unauthorized'], 401);
        }else{
            return response()->json(['message' => 'Authorized'], 200);
        }
    }


}


