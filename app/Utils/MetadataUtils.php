<?php
namespace App\Utils;

use StackUtil\Utils\ApiUtils;
use StackUtil\Utils\Utility;

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
        throw new Exception($object. ' is not found in metadata');
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
        return $field;
        // throw new Exception('{'. $fieldName. '} is not found in object {' .$objectName.'}');
    }

    public static function ValidateField($fieldName, $keys, $value){
        if($fileName){
            if(empty($value) && $value == null || strtolower($value) == 'null'){
                return $keys." is required";
            }
        }
        return $keys;
    }

    public static function CallMetaData($request, $objectName){
        $Url = env('METADATA_URL');
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: '.$request->header('Authorization');

        $result = ApiUtils::Request('GET', $Url.'/metadata/v1?key='.$objectName, $headers, null);
        $metadata = $result->getData(true);
        return $metadata;
    }
}
