<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException As Exception;
use Illuminate\Http\Request As Request;

use StackUtil\Utils\DbUtils;
use App\Utils\MetadataUtils;

use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class DataController extends Controller{

    public function RecordList(Request $request, $tableName, $select = null, $where = null, $orderBy = null){

        $select = $request->input('select');
        $where = $request->input('where');
        $orderBy = $request->input('orderBy');
        try {

            $responsibilityId = self::CheckResponsibility($request);

            if(empty($tableName)){
                return response()->json('Object not found!')->setStatusCode(404);
            }
            // getMetadat of objects (ie. 'account')
            $metadata = MetadataUtils::CallMetaData($request, $tableName);

            // getResponsibilityMetadat of objects (ie. 'account')
            $getResponsibilityMetadata = MetadataUtils::GetResponsibility($request, $responsibilityId);

            // CheckResponsibility
            $checkResponsibility = MetadataUtils::ValidateResponsibility($request, $getResponsibilityMetadata, $metadata, $tableName);

            MetadataUtils::ValidateRequest($request, $metadata, $tableName, null);
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
            $responsibilityId = self::CheckResponsibility($request);
            if(empty($tableName)){
                return response()->json('Object not found!')->setStatusCode(404);
            }
            // getMetadat of objects (ie. 'account')
            $metadata = MetadataUtils::CallMetaData($request, $tableName);

            // getResponsibilityMetadat of objects (ie. 'account')
            $getResponsibilityMetadata = MetadataUtils::GetResponsibility($request, $responsibilityId);

            // CheckResponsibility
            $checkResponsibility = MetadataUtils::ValidateResponsibility($request, $getResponsibilityMetadata, $metadata, $tableName);

            MetadataUtils::ValidateRequest($request, $metadata, $tableName, null);

            $result = DbUtils::generateQuery($tableName,$idOrKey,$select,$where,$orderBy);
            return response()->json($result)->setStatusCode(200);
        } catch(Exception $ex){
            throw $ex;
        }
    }

	public function DeleteRecord(Request $request, $tableName, $idOrKey){
        $where = $request->input('where');

        try {
            $responsibilityId = self::CheckResponsibility($request);
            if(empty($tableName)){
                return response()->json('Object not found!')->setStatusCode(404);
            }
            // getMetadat of objects (ie. 'account')
            $metadata = MetadataUtils::CallMetaData($request, $tableName);

            // getResponsibilityMetadat of objects (ie. 'account')
            $getResponsibilityMetadata = MetadataUtils::GetResponsibility($request, $responsibilityId);

            // CheckResponsibility
            $checkResponsibility = MetadataUtils::ValidateResponsibility($request, $getResponsibilityMetadata, $metadata, $tableName);

            MetadataUtils::ValidateRequest($request, $metadata, $tableName, null);

            $result = DbUtils::generateDeleteRecord($tableName, $idOrKey, $where);
            if($result){
                return response()->json(['message'=>'Record Deleted Successfully.'])->setStatusCode(200); //delete 204 => no content, 200=>with content;
            }else{
                return response()->json(['error'=>'Record {'.$idOrKey.'} Not Found in object {'.$tableName.'}'])->setStatusCode(404); //delete 204 => no content, 200=>with content;
            }
        } catch(Exception $ex){
            throw $ex;
        }
    }

    public function InsertRecord(Request $request, $tableName){

        $data = $request->all();
        try {
            $responsibilityId = self::CheckResponsibility($request);
            if(empty($tableName)){
                return response()->json('Object not found!')->setStatusCode(404);
            }
            $metadata = MetadataUtils::CallMetaData($request, $tableName);

            $getResponsibilityMetadata = MetadataUtils::GetResponsibility($request, $responsibilityId);
            $checkResponsibility = MetadataUtils::ValidateResponsibility($request, $getResponsibilityMetadata, $metadata, $tableName);

            $datas = MetadataUtils::ValidateRequest($request, $metadata, $tableName, $data);
            if(isset($datas)){
                $result = DbUtils::generateInsert($tableName, $datas);
                if($result){
                    $insertedRecord = DbUtils::generateQuery($tableName,$datas['id']);
                    return response()->json($insertedRecord)->setStatusCode(201);
                }else{
                    return response()->json(['error'=>$result])->setStatusCode(500);
                }
            }
        } catch(Exception $ex){
            throw $ex;
        }
    }

    public function UpdateRecord(Request $request, $tableName, $idOrKey){
        $data = $request->all();
        try {
            $responsibilityId = self::CheckResponsibility($request);
            if(empty($tableName)){
                return response()->json('Object not found!')->setStatusCode(404);
            }

            $metadata = MetadataUtils::CallMetaData($request, $tableName);
            $getResponsibilityMetadata = MetadataUtils::GetResponsibility($request, $responsibilityId);
            $checkResponsibility = MetadataUtils::ValidateResponsibility($request, $getResponsibilityMetadata, $metadata, $tableName);
            $data = MetadataUtils::ValidateRequest($request, $metadata, $tableName, $data);

            $result = DbUtils::generateUpdateRecord($request, $tableName, $idOrKey);
            $updatedRecord = DbUtils::generateQuery($tableName ,$idOrKey);
            if($result){
                return response()->json($updatedRecord)->setStatusCode(200);
            }else{
                return response()->json(['error'=>$updatedRecord])->setStatusCode(500);
            }
        } catch(Exception $ex){
            throw $ex;
        }
    }

    public function CheckResponsibility($request){
        $payload = MetadataUtils::GetResponsibilityId($request);
        $responsibilityId = $payload['user_info']->responsibility_id;
        if(empty($responsibilityId)){
            return response()->json([
                    'status'=>'error',
                    'message' => '{'.$payload['user_info']->name.'} User is don\'t have any Responsibility',
                    'created' => gmdate("F j, Y, g:i a")
                ],404);
        }else{
            return $responsibilityId;
        }
    }


}
