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

    public function getObject(Request $request, $tableName = null, $id = null){
        $qureyInfo = $request->all();

        // echo $id;
        /* echo "<pre>";
        print_r($tableName);
        print_r($qureyInfo);
        dd($request); */

        $result = (new DataUtilsController)->genericSelect($tableName,$qureyInfo,$id);
        return (['results'=>$result]);
     }

/*    public function getUserPosts($id)
    {
        $user = User::find($id)->posts()->get();
        return ($user);
        
    } */
}


