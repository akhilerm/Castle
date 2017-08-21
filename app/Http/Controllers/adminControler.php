<?php

namespace App\Http\Controllers;

use Request;

class adminControler extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware('admin');
    }

    public function shell()
    {
        if (Request::ajax()){
            $req = Request::all();

            //check if func exist and call
            if (method_exists($this, $req['method'])){
                return call_user_func( array($this, $req['method']),$req);
            }

            // The response
            return response()->json($req);
        }
    }

    public function cd($args){
       $result['status']="Hello";
       $result['MSG']="World";
       return response()->json($result);
    }

    public function request($args){
        //Do stuff here
    }


}
