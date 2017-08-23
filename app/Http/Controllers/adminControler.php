<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Request;

class adminControler extends Controller
{
    //

    public function shell()
    {
        if (Request::ajax()){

            $req = Request::all();
            $settings = ['CMD_COLOR' =>  '#FFA500', 'DIR_COLOR' => '#0000FF', 'WORK_DIR' => 'public/'];

            //check if func exist and call
            if (method_exists($this, $req['method'])){
                return call_user_func_array( array($this, $req['method']),[$req['args'], $settings]);
            }

            // The response
            return response()->json(['args'=>$req['method']]);
        }
    }


    /**
     * CHANGE DIR: if arg is .. switch to home else switch to folder if exists
     *
     * @param $args
     * @param $settings
     * @return \Illuminate\Http\JsonResponse
     */

    public function cd($args, $settings)
    {

        if ( $args[0] === '..' || $args[0] === '~'){

            Session::put('pwd', '~');
            $msg = Auth::user()['name'].'@Castle:'.session('pwd').'$ ';
            $sts = true;

        }
        elseif ( $args[0] !==  '.'){

            //ADDRESS TO  Users home directory
            $user_dir = $settings['WORK_DIR'].Auth::id();

            //Check if the folder exists if in home
            if(Session::get('pwd') === '~'){

                $user_dir = "$user_dir/$args[0]";
                if(Storage::has("$user_dir/")){

                    Session::put('pwd', "~/$args[0]");
                    $msg = Auth::user()['name'].'@Castle: '.session('pwd').'$ ';
                    $sts = true;
                    return response()->json( ['STS'=> $sts, 'MSG' => $msg] );

                }

            }

            //No Directory
            $msg="cd: $args[0]: No such directory";
            $sts=false;

        } else{

            //Keeping it in the same directory
            $msg = Auth::user()['name'].'@Castle: '.session('pwd').'$ ';
            $sts = true;

        }

        return response()->json( ['STS'=> $sts, 'MSG' => $msg] );
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

    /**
     * Return a String as response
     *
     * @param $args
     * @param $settings
     * @return \Illuminate\Http\JsonResponse
     */

    public function help($args, $settings)
    {

        $msg  = "\nUse the following shell commands: \n[[;" . $settings['CMD_COLOR']. ";]cd]     - change directory [dir_name] \n[[;" . $settings['CMD_COLOR']. ";]cat]    - print file [file_name] \n[[;" . $settings['CMD_COLOR']. ";]clear]  - clear the terminal \n[[;" . $settings['CMD_COLOR']. ";]edit]   - open file in editor [file_name] \n[[;" . $settings['CMD_COLOR']. ";]ls]     - list directory contents [dir_name] \n[[;" . $settings['CMD_COLOR']. ";]logout] - logout from Castle \n[[;" . $settings['CMD_COLOR']. ";]request]- request a new challenge \n[[;" . $settings['CMD_COLOR']. ";]status] - print progress \n[[;" . $settings['CMD_COLOR']. ";]submit] - submit final solution for assessment [file_name] \n[[;" . $settings['CMD_COLOR']. ";]verify] - runs tests on solution file [file_name]\n";
        return response()->json([ 'STS' => true, 'MSG' => $msg]);

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

    public function request($args, $settings)
    {
        $dir = $settings['WORK_DIR'].'users/'.Auth::id();
        $list = $this->ls($dir, $settings);

        // contains DIR color then, a folder is already present.
        if (strpos($list['MSG'], $settings['DIR_COLOR']) !== false) {

            $msg = 'You can request a new challenge only after completing the current challenge\n';

        }
        else {

            $user_level = $this->getLevelData();

            //Check the current status of user and increment it unless game is over
            if ($user_level['level'] == $user_level['max_level'] && $user_level['sublevel'] == $user_level['cur_max_sublevel']) {

                $msg = 'No more challenges. You did it.';
                return response()->json([ 'STS' => true, 'MSG' => $msg]);

            }
            elseif ($user_level['sublevel'] == $user_level['cur_max_sublevel']) {

                $user_level['sublevel'] = 1;
                $user_level['level']++;

            }
            else {

                $user_level['sublevel']++;

            }

            //add code to start new countdown
            $row = DB::select("select id, name from levels where level_no = ".$user_level['level']." and sublevel_no = ".$user_level['sublevel']." order by rand() limit 1");

        }
    }

    function check($command, $args) {
        return true;
    }


    /**
     * CHANGE DIR: if arg is .. switch to home else switch to folder if exists
     *
     * @return array current level, current sublevel, max level and max sublevel
     */

    function getLevelData()
    {
        
    }
}
