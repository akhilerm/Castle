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
        $this->WORK_DIR = '/home/akhil/Work/HTML/www/term/storage/app/public/';
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
            $result['MSG'] = "cat: ".$args[0].": No such file";
            $result['STS'] = false;
            return response()->json($result);
        }
    }

    public function help($args)
    {
        if ($this->check("help", $args)) {
            $result['MSG'] = "\nUse the following shell commands:
[[;" . $this->CMD_COLOR . ";]cd]     - change directory [dir_name]
[[;" . $this->CMD_COLOR . ";]cat]    - print file [file_name]
[[;" . $this->CMD_COLOR . ";]clear]  - clear the terminal
[[;" . $this->CMD_COLOR . ";]edit]   - open file in editor [file_name]
[[;" . $this->CMD_COLOR . ";]ls]     - list directory contents [dir_name]
[[;" . $this->CMD_COLOR . ";]logout] - logout from Castle
[[;" . $this->CMD_COLOR . ";]request]- request a new challenge
[[;" . $this->CMD_COLOR . ";]status] - print progress
[[;" . $this->CMD_COLOR . ";]submit] - submit final solution for assessment [file_name]
[[;" . $this->CMD_COLOR . ";]verify] - runs tests on solution file [file_name]\n";
            $result['STS'] = true;
            return response()->json($result);
        }
    }

    public function ls($args) {
        if ($this->check("ls", $args)) {
            $dir = $this->WORK_DIR.'/users/'.Auth::user()['id'].'/';
            if (session('pwd')!='~') {
                $dir = $dir.explode("/", session('pwd'))[1].'/';
            }
            $files = preg_grep('/^([^.])/', scandir($dir));
            $result['MSG']="\n";
            foreach ($files as $file) {
                if (is_dir($dir.$file)) {
                    $result['MSG'] = $result['MSG']."[[;".$this->DIR_COLOR.";]".$file."/]\n";
                }
                else
                    $result['MSG'] = $result['MSG'].$file."\n";
            }
            $result['STS']=true;
        }
        return response()->json($result);
    }

    public function request($args){
        //Do stuff here
    }

    function check($command, $args) {
        return true;
    }


}
