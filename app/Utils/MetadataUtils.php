<?php
namespace App\Utils;

use StackUtil\Utils\ApiUtils;
use StackUtil\Utils\Utility;
use StackUtil\Utils\DbUtils;
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
        $method = $request->getMethod(); // 'POST'
        $validate = false;

        switch ($method) {
            case 'GET':
                $validate = MetadataUtils::validateGetRequest($request, $metadata, $objectName);
                break;
            case 'POST':
                $validate = MetadataUtils::validatePostRequest($metadata, $objectName, $data, true);
                break;
            case 'PATCH':
                $validate = MetadataUtils::validatePostRequest($metadata, $objectName, $data, false);
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
                $fileName = MetadataUtils::GetField($metadata, $objectName, $keys);
            }
        }

        if(isset($where)){
            $whereResult = explode( ",", $where );
            $whereArray = DbUtils::generateKeyValueWithOperators($whereResult);
            foreach ($whereArray as $keys){
                $fileName = MetadataUtils::GetField($metadata, $objectName, $keys['key']);
            }
        }

        if(isset($orderBy)){
            $orderByResult = explode( ",", $orderBy);
            foreach ($orderByResult as $keys){
                if($keys[0] == '-'){
                    $fileName = MetadataUtils::GetField($metadata, $objectName, ltrim($keys,'-'));
                }else{
                    $fileName = MetadataUtils::GetField($metadata, $objectName, ltrim($keys));
                }
            }
        }
        return $fileName;
    }

    public static function ValidatePostRequest($metadata, $objectName, $data, $isMandatoryCheck)
    {
        $object = MetadataUtils::GetObject($metadata,$objectName);
        //validated fields check column exist in tfield
        foreach ($data as $keys => $value){
            $fileName = MetadataUtils::GetField($metadata,$objectName,$keys);
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

        $result = ApiUtils::Request('GET', $Url.'/metadata/v1?key='.$objectName, $headers, null);
        $metadata = $result->getData(true);

        if(isset($metadata['error'])){
            throw new Exception($metadata['error'],$metadata['status']);
        }
        return $metadata;
    }
}
