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

        $whereData = $this->getWhereData($id);
        
        $columns = $this->getColumnName($columnName);

        $query = DB::table($tblName)->select($columns);

        if(!empty($id)){
            $query = $query->where($Id);
        }
        $results = $query->get();

        dd($results);

        // return $columnName;

        die;

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


        return $whereData;
    }

    public function getColumnName($columnName = null){
        
        if(sizeof($columnName) == 0){
            // echo sizeof($columnName);
            // return sizeof($columnName);
            $columnName['select'] = "id,name,prefix";
            // return $columnName;
        }

        $tblFielsdName = explode( ",", $columnName['select'] );
        return $tblFielsdName;
    }


    /* public function genericSelect($tblName = null,$columnName = null,$id = null){
        echo "<pre>";
        if(!empty($id)){
            echo $id;
        }else{
            echo "5555";
        }
        if(empty($columnName)){
            echo "jonj";
        }else{
            echo "kkkkkk";
        }


        if(!empty($id) && empty($columnName)){
            $query = DB::table($tblName)->select([])->where('id', "$id");
            dd($query);
            die;
            $results = $query->get();

        }

       
        die;

        return "Ganesh";
        $explodeResult = explode( ",", $columnName['select'] );
        // $result = "['" .implode ("', '", $explodeResult). "']";
        print_r($columnName);
        // ->where(['id=1'])
        try { 
                $query = DB::table($tblName)->select($explodeResult)->where('name', 'ram');
                // Closures include ->first(), ->get(), ->pluck(), etc.
                print_r($query);
                return $results = $query->get();
                // return $results;
          } catch(Exception $ex){ 
                // dd($ex->getMessage()); 
                return $ex->getMessage();
                // Note any method of class PDOException can be called on $ex.
          }
    } */

    /* public function genericWhereClouse($where){
        dd($where);
        return $where;
    } */

    //
}
