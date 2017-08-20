<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Server;

class adminControler extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        include app_path().'/functions/json-rpc.php';
        handle_json_rpc(new Server());
        return view('dasboard');
        /*$result[] = "message received";
        return $result;*/
    }
}
