<?php
namespace App\Http\Controllers;

use App\Http\Controllers\DataUtilsController;
use Illuminate\Http\Request;




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
    }

    public function getObject(Request $request, $tableName = null, $id = null, $select = null, $where = null){
        $qureyInfo = $request->all();
        // $selects = $_GET['select'];//$request->input('select');
        // echo $select;
        print_r($select);
        // print_r($where);

        die;

        $result = (new DataUtilsController)->genericSelect($tableName,$qureyInfo,$id);
        return (['results'=>$result]);
     }

/*    public function getUserPosts($id)
    {
        $user = User::find($id)->posts()->get();
        return ($user);
        
    } */
}


