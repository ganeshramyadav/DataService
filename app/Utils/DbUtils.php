<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException As Exception;
use Illuminate\Http\Request;

class DbUtils
{
    private $test;

    /* function __construct()
    {
        parent::setUp();
    } */

    public static function aaaa($test = null){
        return $test;
        /* return json([
            'success' => false,
            'status' => 401,
            'message' => 'HTTP_UNAUTHORIZED'
          ], 401); */
        // return {['result'=>"success",'status'=>"200"]};
    }
    
    public static function generateQuery($objName,$idOrKey=null, $select = null, $where = null, $orderBy = null){
       echo "55555555";
        print_r($select);

        die;
        return "Abc";
    }

    public static function generateSelect($select = null)
    {
        if(sizeof($select) == 0){
            // echo sizeof($columnName);
            // return sizeof($columnName);
            $columnName['select'] = "id,name,prefix";
            // return $columnName;
        }

        /* if(sizeof($columnName) == 0){
            // echo sizeof($columnName);
            // return sizeof($columnName);
            $columnName['select'] = "id,name,prefix";
            // return $columnName;
        }

        $tblFielsdName = explode( ",", $columnName['select'] );
        return $tblFielsdName; */
    }
}
