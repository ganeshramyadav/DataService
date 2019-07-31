<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException As Exception;
use Illuminate\Http\Request;

class DataUtilsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function genericSelect($tblName = null,$columnName = null,$id = null){
        $Id = $this->getId($id);
        // print_r($Id);
        // echo"<pre>";
        print_r(sizeof($columnName['where']));

        $columns = $this->getColumnName($columnName);

        // print_r($columns);
        
        $query = DB::table($tblName)->select($columns);

        if(!empty($id)){
            // echo "gnaeshId";
            $query = $query->where($Id);
        }elseif(sizeof($columnName['where']) != 0){
            // echo "gnaeshColumns";
            
            $whereData = $this->getWhereData($columnName);
            $query = $query->where($whereData);
            
            
            // print_r($whereData);
            // print_r($query);
            // die;
        }
        $results = $query->get();
        /* echo "end";
        die; */
        // dd($results);
        return $results;
        // return $columnName;

        // die;

    }



    public function getId($id = null){

        $whereData = [
            ['id', '=', $id]
        ];


        return $whereData;
        /* $result = $id;
        // echo $result;die;
        return $result; */
    }

    public function getWhereData($data){
        // echo $data;
        // ['id', '=', $id]
        // echo($data);
        // print_r($data);
        // die;
        $explode = explode("=",$data['where']);
        // echo $data['where'];
        if(preg_match("{=}", $data['where'])){
            // echo "true";
            $finalData = "'".$explode[0]."','=' ,".$explode[1];
           
        }else{
            echo "false";
        }
        // die;
        $whereData = [
            [$finalData]
        ];
        
        // print_r($whereData);
        // die;
        return $whereData;
    }

    public function getColumnName($columnName = null){
        
        if(sizeof($columnName) == 0){
            $columnName['select'] = "id,name,prefix";
        }

        $tblFielsdName = explode( ",", $columnName['select'] );
        return $tblFielsdName;
    }


    
}
