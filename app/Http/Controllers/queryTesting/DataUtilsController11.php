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


    /*
        'name','key','prefix'

    */
    public function genericSelect($tblName = null,$columnName = null,$id = null){
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
    }

    public function genericWhereClouse($where){
        dd($where);
        return $where;
    }

    //
}
