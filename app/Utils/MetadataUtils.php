<?php
namespace App\Utils;
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


    throw new Exception('{'. $filedName. '} is not found in object {' .$objectName.'}');

}



}



?>