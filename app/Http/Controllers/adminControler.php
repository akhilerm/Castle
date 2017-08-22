<?php

namespace App\Http\Controllers;

use Request;

class adminControler extends Controller
{
    //
    private $CMD_COLOR;
    private $DIR_COLOR;
    private $WORK_DIR;
    public function __construct()
    {
        //$this->middleware('admin');
        $this->CMD_COLOR = '#FFA500';
        $this->DIR_COLOR = '#0000FF';
        $this->WORK_DIR = '/home/akhil/Work/HTML/www/term/';
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
        if ($this->check("cd", $args)) {
            if ($args[0] == ".."){
                session(['pwd'=>'~']);
            }
            else if ($args[0] != "."){
                $dir = $this->WORK_DIR.'/users/'.Auth::user()['id'].'/';
                if (session('pwd')!='~') {
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
                    session(['pwd'=>"~/".$folder]);
                }
                else{
                    $result[0] = "\ncd: ".$args[0].": Not a directory\n";
                    $result[1] = "false";
                    return $result;
                }

            }

            $MSG=Auth::user()['name'].'@Castle:'.session('pwd').'/$ ';
            $STS=true;
        }
        else {
            $MSG='cd: '.args[0].': No such directory';
            $STS=false;
        }
        $result['STS']=$STS;
        $result['MSG']=$MSG;
        return response()->json($result);
    }

    public function cat($args) {
        if ($this->check("cat", $args)) {
            $dir = $this->WORK_DIR.'/users/'.Auth::user()['id'].'/';
            if (session('pwd')!='~') {
                $dir = $dir.explode("/", session('pwd'))[1].'/';
            }
            $files = preg_grep('/^([^.])/', scandir($dir));
            foreach ($files as $file) {
                if (is_file($dir.$file) && $file == $args[0]) {
                    $result['MSG'] = file_get_contents($dir.$file);
                    $result['STS']=true;
                    return response()->json($result);
                }
            }
            $result[] = "cat: ".$args[0].": No such file";
            return $result;
        }
    }

    

    public function request($args){
        //Do stuff here
    }

    function check($command, $args) {
        return true;
    }


}
