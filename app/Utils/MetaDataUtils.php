<?php

namespace App\Utils;


class MetaDataUtils
{
    // $object = 'TableName';
    // $field = 'FieldNameOfTable';
    // $metadata = 'CheckFieldNameInMetaData';

    public static function GetObject($metadata,$objectName)
    {

        $object = Utils::objArraySearch($metadata, 'key', $objectName);
        
        if(!empty($object) && $object != null)
        {
            return $object;
        }else 
        {
            $object = Utils::objectArraySearch($metadata,'id',$objectName);
            if(!empty($object) && $object != null)
            {
                return $object;
            }else
            {
            throw new Exception($objectName.' is not present in metadata');
            }
        }
    }

    public static function GetField($metadata, $objectName, $fieldName)
    {
       $object = GetObject($metadata,$objectName);
       $field = Utils::objectArraySearch($object->columns__r,'key',$fieldName);
       if(!empty($field) && $field != null){
           return $field;
       }else{
            $field = Utils::objectArraySearch($object->columns__r,'id',$fieldName);
            if(!empty($field) && $field != null)
            {
                return $field;
            }else
            {
            throw new Exception($fieldName.' is not present in '.$objectName);
            }
       }
    }
}
