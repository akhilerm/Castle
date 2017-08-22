<?php

namespace App\Http\Controllers;

use Request;

class adminControler extends Controller
{
    //
    private $CMD_COLOR=;
    private $DIR_COLOR=;
    private $WORK_DIR=;
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
        /*if ($this->check("cd", $args)) {
            if ($args[0] == ".."){
                session(['pwd'=>'~']);
            }
            else if ($args[0] != "."){
                $dir = $this->WORK_DIR.'/users/'.Auth::user()['id'].'/';
                if ($_SESSION['PWD']!='~') {
                    $dir = $dir.explode("/", session('pwd'))[1].'/';
                }
                $files = preg_grep('/^([^.])/', scandir($dir));
                $folder = "NIL";
                foreach ($files as $file) {
                    if (is_dir($dir.$file)) {
                        $folder = $file;
                    }
                }
                if ($folder!="NIL" && $folder ==$args[0]) {
                    $_SESSION['PWD']="~/".$folder;
                }
                else{
                    $result[0] = "\ncd: ".$args[0].": Not a directory\n";
                    $result[1] = "false";
                    return $result;
                }

            }

            $result[0]=$_SESSION['USER_NAME'].'@Castle:'.$_SESSION['PWD'].'/$ ';
            $result[1]="true";
            return $result;
        }*/
        session(['pwd'=>'~']);
        $result['STS']=true;
        $result['MSG']=session('pwd');
        return response()->json($result);
    }

    public function request($args){
        //Do stuff here
    }

    function check($command, $args) {
        return true;
    }


}
