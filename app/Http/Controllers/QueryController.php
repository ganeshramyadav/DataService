<?php
namespace App\Http\Controllers;

use App\Http\Controllers\DataUtilsController;
use Illuminate\Http\Request;
use App\Utils\DbUtils as Dbutils;


// use Illuminate\Support\Facades\DB;
// use Illuminate\Database\QueryException;
// use DB;


class QueryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        // $_dbutilsObj = new Dbutils;
    }

    public function getObject(Request $request, $tableName = null, $idOrKey = null, $select = null, $where = null, $orderBy = null){
        $qureyInfo = $request->all();
        $select = $request->input('select');
        $where = $request->input('where');
        $orderBy = $request->input('orderBy');

        
        $_dbutilsObj = new Dbutils;
        $results = $_dbutilsObj->aaaa("test");
        $res = testing();

        die;


        /* print_r($results);
        die;
        include(app_path() . '\Utils\DbUtils.php');
        $result = getTest();
        echo $result;
        // $result = DbUtils.generateQueryone($tableName);
        // die;
        // dd($request);
        // echo "ganesfh";die; */



        try { 
            if(empty($tableName)){
                return "tableName is Empty.";
            }
            if(!empty($idOrKey)){
                $result = (App\Utils\DbUtils).generateQuery($tableName,$idOrKey,'*');
                //return "single record. return json Object.";
                return $result;
            }

            echo "ganeshf";

            $result = DbUtils.generateQuery($tableName,null,$select,$where,$orderBy);
            print_r($result);
            die;

        } catch(Exception $ex){ 
            // dd($ex->getMessage()); 
            return $ex->getMessage();
            // Note any method of class PDOException can be called on $ex.
        }



        // $selects = $_GET['select'];//$request->input('select');
        echo $select;
        echo $where;
        echo $orderBy;
        // print_r($select);
        // print_r($where);
        // if
        die;

        // $result =  DbUtils.generateQuery($tableName,$qureyInfo,$id);
        // $result =  DbUtils->genericSelect($tableName,$qureyInfo,$id);
        return (['results'=>$result]);
     }

/*    public function getUserPosts($id)
    {
        $user = User::find($id)->posts()->get();
        return ($user);
        
    } */
}


