<?php
namespace App\Utils;

use StackUtil\Utils\ApiUtils;
use StackUtil\Utils\Utility;
use StackUtil\Utils\DbUtils;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class MetadataUtils {
    public static function GetObject($metadata,$objectName){

        $object = Utility::objArraySearch($metadata['objects'],'key',$objectName);

        if(!empty($object) && $object != null)
        {
            return $object;
        }
        $object = Utility::objArraySearch($metadata,'id',$objectName);
            if(!empty($object) && $object != null)
        {
            return $object;
        }
        throw new Exception($object. ' is not found in metadata',500);
    }

    public static function GetField($metadata,$objectName,$fieldName)
    {
        $object = MetadataUtils::GetObject($metadata,$objectName);
        $field = Utility::objArraySearch($object['columns__r'],'id',$fieldName);
        if(!empty($field) && $field != null)
        {
            return $field;
        }
        $field = Utility::objArraySearch($object['columns__r'],'key',$fieldName);
        if(!empty($field) && $field != null)
        {
            return $field;
        }
        throw new Exception('{'. $fieldName. '} is not found in object {' .$objectName.'}',500);
    }

    public static function ValidateRequest($request, $metadata, $objectName, $data = null){
        $method = $request->getMethod();
        $validate = false;

        switch ($method) {
            case 'GET':
                $validate = MetadataUtils::ValidateGetRequest($request, $metadata, $objectName);
                break;
            case 'POST':
                $validate = MetadataUtils::ValidatePostRequest($metadata, $objectName, $data, true);
                break;
            case 'PATCH':
                $validate = MetadataUtils::ValidatePostRequest($metadata, $objectName, $data, false);
                break;
        }
        return $validate;
    }

    public static function ValidateGetRequest($request, $metadata, $objectName)
    {
        $select = $request->input('select');
        $where = $request->input('where');
        $orderBy = $request->input('orderBy');

        if(isset($select)){
            $selectResult = explode( ",", $select );
            foreach ($selectResult as $keys){
                MetadataUtils::GetField($metadata, $objectName, $keys);
            }
        }

        if(isset($where)){
            $whereResult = explode( ",", $where );
            $whereArray = DbUtils::generateKeyValueWithOperators($whereResult);
            foreach ($whereArray as $keys){
                MetadataUtils::GetField($metadata, $objectName, $keys['key']);
            }
        }

        if(isset($orderBy)){
            $orderByResult = explode( ",", $orderBy);
            foreach ($orderByResult as $keys){
                if($keys[0] == '-'){
                    MetadataUtils::GetField($metadata, $objectName, ltrim($keys,'-'));
                }else{
                    MetadataUtils::GetField($metadata, $objectName, ltrim($keys));
                }
            }
        }
    }

    public static function ValidatePostRequest($metadata, $objectName, $data, $isMandatoryCheck)
    {
        $object = MetadataUtils::GetObject($metadata,$objectName);
        //validated fields check column exist in tfield
        foreach ($data as $keys => $value){
            MetadataUtils::GetField($metadata,$objectName,$keys);
        }
        if($isMandatoryCheck){
            return $data = MetadataUtils::ValidateMandatoryFields($object, $data);
        }else{
            return $data;
        }

    }

    public static function ValidateMandatoryFields($object, $data)
    {
        $data['id'] = Utility::generateId('s',$object['short_name']);
        $data['key'] = Utility::generateKey($object['short_name']);
        // check mendatory fields
        $requiredFields = array_filter($object['columns__r'],function ($column){
            if($column['required'] === 1)
            {
                return  $column;
            }
        });
        foreach($requiredFields as $fields){
            $key = $fields['key'];
            if(isset($data) && !isset($data[$key])){
                throw new Exception("{".$key."} is required to perform the action.",400);
            }
        }
        return $data;
    }

    public static function CallMetaData($request, $objectName){
        $Url = env('METADATA_URL');
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: '.$request->header('Authorization');
        $headers[] = 'skipAuth: ture';

        $result = ApiUtils::Request('GET', $Url.'/metadata/v1?key='.$objectName, $headers, null);
        $metadata = $result->getData(true);

        if(isset($metadata['error'])){
            throw new Exception($metadata['error'],$metadata['status']);
        }
        return $metadata;
    }

    public static function GetResponsibility($request, $ersponsibilityId)
    {
        $method = $request->getMethod();
        $Url = env('METADATA_URL');
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: '.$request->header('Authorization');
        $headers[] = 'skipAuth: ture';

        $result = ApiUtils::Request('GET', $Url.'/metadata/v1/responsibility/'.$ersponsibilityId, $headers, null);
        $metadata = $result->getData(true);

        if(isset($metadata['error'])){
            throw new Exception($metadata['error'],$metadata['status']);
        }
        return $metadata;
    }

    public static function ValidateResponsibility($request, $getResMetadatas, $metadata, $objName)
    {
        $method = $request->getMethod();

        $object = self::GetObject($metadata, $objName);
        $objectResponsibility = Utility::objArraySearch($getResMetadatas['object__r'], 'object_id', $object['id']);

        if(!isset($objectResponsibility)){
            throw new Exception('Not Authorized to perform operation for {'.$objName.'} object');
        }

        if($objectResponsibility['active'] === 0){
            throw new Exception('Insufficient permission for {'.$objName.'} object ');
        }

        switch ($method){
            case 'GET':
                if($objectResponsibility['readable'] === 0){
                    throw new Exception('Not Authorized to perform read operation for {'.$objName.'} object');
                }
                break;
            case 'POST':
                if($objectResponsibility['writable'] === 0){
                    throw new Exception('Not Authorized to perform write operation for {'.$objName.'} object');
                }
                break;
            case 'PATCH':
                if($objectResponsibility['updatable'] === 0){
                    throw new Exception('Not Authorized to perform update operation for {'.$objName.'} object');
                }
                break;
            case "DELETE":
                if($objectResponsibility['deletable'] === 0){
                    throw new Exception('Not Authorized to perform delete operation for {'.$objName.'} object');
                }
                break;
        }
    }

    public static function GetUserDetails($request)
    {
        $token = $request->header('Authorization');
        $explode = explode('Bearer ', $token);
        // return $explode[1];
        // $token = JWTAuth::getToken();
        return $apy = JWTAuth::getPayload($explode[1])->toArray();
    }

}
