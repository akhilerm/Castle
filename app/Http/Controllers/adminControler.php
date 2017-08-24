<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models;
use Request;


class adminControler extends Controller
{
    //

     public function shell()
    {
        if (Request::ajax()){

            $req = Request::all();
            if (!isset($req['args'])){
                $req['args'] = [false];
            }
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
        if ($args[0] !== false ) {
            if ($args[0] === '..' || $args[0] === '~') {

                Session::put('pwd', '~');
                $msg = Auth::user()['name'] . '@Castle:'. session('pwd') . '$ ';
                $sts = true;

            } elseif ($args[0] !== '.') {

                //ADDRESS TO  Users home directory
                $user_dir = $settings['WORK_DIR'] .'users/'. Auth::id();

                //Check if the folder exists if in home
                if (Session::get('pwd') === '~') {
                    $user_dir = "$user_dir/$args[0]";
                    if (Storage::has("$user_dir/")) {
                        Session::put('pwd', $args[0]);
                        $msg = Auth::user()['name'] . '@Castle:' . session('pwd') . '$ ';
                        $sts = true;
                        return response()->json(['STS' => $sts, 'MSG' => $msg]);
                    }
                }

                //No Directory
                $msg = "cd: $args[0]: No such directory";
                $sts = false;

            } else {

                //Keeping it in the same directory
                $msg = Auth::user()['name'] . '@Castle:' . session('pwd') . '$ ';
                $sts = true;

            }
        } else{

            $sts = false;
            $msg = "No Directory";

        }

        return response()->json( ['STS'=> $sts, 'MSG' => $msg] );

    }

    /**
     * Display contents of file.
     *
     * @param $args
     * @param $settings
     * @return \Illuminate\Http\JsonResponse
     */
    public function cat($args, $settings) {

        $msg = 'No Such file';
        if ($args[0] !== false) {
            //calculating present directory
            $user_dir = $user_dir = $settings['WORK_DIR'] .'users/'. Auth::id() . '/';
            if (Session::get('pwd') !== '~') {
                $user_dir = $user_dir . Session::get('pwd') . '/';
            }
            $user_dir = "$user_dir$args[0]";

            //Check IF file exist and get content
            if (Storage::has($user_dir)) {
                $msg = Storage::get($user_dir);

                //check if the file is a directory
                if ($msg == '')
                    $msg = "cat: $args[0]: is a directory";
            }
        }
        return response()->json([ 'MSG' => $msg , 'STS'=> true]);

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

    /**
     * Function  to list the files in directory (present directory if arguments is null)
     *
     * @param $args
     * @param $settings
     * @return \Illuminate\Http\JsonResponse
     */
    public function ls($args,$settings) {

        //calculating present directory
        $user_dir = $user_dir = $settings['WORK_DIR'].'users/'.Auth::id().'/';
        if (Session::get('pwd') !== '~' ){
            $user_dir = $user_dir.Session::get('pwd').'/';
        }
        if ( $args[0] !== false){
            $user_dir = "$user_dir$args[0]/";
        }
        $msg ='';
        $user_dir = strtr($user_dir, ['//' => '/']);
        if (Storage::has($user_dir)){
            $files = Storage::files($user_dir);
            $dirs = Storage::directories($user_dir);

            //add color to directory
            if (sizeof($dirs) > 0)
                $dirs[0] = "[[;".$settings['DIR_COLOR'].";]$dirs[0]]";

            $all_files = array_merge($files, $dirs);

            //Fixing Format
            $count = 0;
            foreach ($all_files as $file){
                if ($count !== 0){
                    $msg  = "$msg\n";
                }
                $file = strtr($file, [$user_dir => '']);
                $msg = "$msg$file";
                $count++;
            }

        }

        return response()->json([ 'MSG' => $msg , 'STS'=> true]);
    }

    public function request($args, $settings)
    {
        if ($args[0] !== false) {
            $dir = $settings['WORK_DIR'] . 'users/' . Auth::id();
            $list = $this->ls($dir, $settings);

            // contains DIR color then, a folder is already present.
            if (strpos($list['MSG'], $settings['DIR_COLOR']) !== false) {

                $msg = 'You can request a new challenge only after completing the current challenge\n';

            } else {

                $user_level = $this->getLevelData();

                //Check the current status of user and increment it unless game is over
                if ($user_level['level'] == $user_level['max_level'] && $user_level['sublevel'] == $user_level['max_sublevel']) {

                    $msg = 'No more challenges. You did it.';
                    return response()->json([ 'STS' => true, 'MSG' => $msg]);

                }
                elseif ($user_level['sublevel'] == $user_level['max_sublevel']) {

                    $user_level['sublevel'] = 1;
                    $user_level['level']++;

                }
                else {

                    $user_level['sublevel']++;

                }

                //add code to start new countdown
                $row = DB::select("select id, name from levels where level_no = ".$user_level['level']." and sublevel_no = ".$user_level['sublevel']." order by rand() limit 1");
                return response()->json(['STS' => true, 'MSG' => $msg]);
            }
        }
        else {
            $sts = false;
            $msg = 'request: too many arguments';
        }
        return response()->json(['STS' => $sts, 'MSG' => $msg]);
    }

    /**
     * GET LEVEL: get all level associated data of the current user
     *
     * @return array current level, current sublevel, max level and max sublevel of current level
     */
    function getLevelData()
    {
        $level_id = Models\user::find(Auth::id())->first();
        $level_data = Models\level::find($level_id->level_id);
        $level = $level_data->level;
        $sublevel = $level_data->sub_level;
        $max_level = Models\level::orderBy('level', 'DESC')->first()->level;
        $max_sublevel = Models\level::where('level', '=', $level)->orderBy('sub_level', 'DESC')->first()->sub_level;
        return array('level' => $level, 'sublevel' => $sublevel, 'max_level' => $max_level, 'max_sublevel' => $max_sublevel);
    }

    /**
     * CHECK ARGUMENTS: validate arguments passed for a command
     * @param $command
     * @param $args
     * @return boolean true if correct number of arguments for the command else false
     */
    function check($command, $args)
    {
        //write code to check each command and its function.
        return true;
    }
}
