<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models;
use Psy\Util\Str;
use Request;

class ShellContoller extends Controller
{
    //

    public function shell()
    {
        if (Request::ajax()){

            $req = Request::all();

            $settings = ['CMD_COLOR' =>  '#FFA500', 'DIR_COLOR' => '#0000FF', 'WORK_DIR' => 'public/'];

            if (!isset($req['method'])) {
                $msg = '';
            }
            elseif (!method_exists($this, $req['method'])) {
                $msg = $req['method'].": command not found\nType [[;".$settings['CMD_COLOR'].";]help] for a list of available commands";
            }
            elseif ($this->check($req)) {
                if (!isset($req['args'])){
                    $req['args'] = [false];
                }
                return call_user_func_array( array($this, $req['method']),[$req['args'], $settings]);
            }
            else {
                $msg = $req['method'].": invalid no. of parameters";
            }

            return response()->json(['STS' => false, 'MSG' => $msg]);
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
        $msg = "\nUse the following shell commands: \n[[;" . $settings['CMD_COLOR']. ";]cd]     - change directory [dir_name] \n[[;" . $settings['CMD_COLOR']. ";]cat]    - print file [file_name] \n[[;" . $settings['CMD_COLOR']. ";]clear]  - clear the terminal \n[[;" . $settings['CMD_COLOR']. ";]edit]   - open file in editor [file_name] \n[[;" . $settings['CMD_COLOR']. ";]help]   - display this message \n[[;" . $settings['CMD_COLOR']. ";]ls]     - list directory contents [dir_name] \n[[;" . $settings['CMD_COLOR']. ";]logout] - logout from Castle \n[[;" . $settings['CMD_COLOR']. ";]request]- request a new challenge \n[[;" . $settings['CMD_COLOR']. ";]status] - print progress \n[[;" . $settings['CMD_COLOR']. ";]submit] - submit final solution for assessment [file_name] \n[[;" . $settings['CMD_COLOR']. ";]verify] - runs tests on solution file [file_name]\n";
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
        $user_dir = $settings['WORK_DIR'].'users/'.Auth::id().'/';
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

    /**
     * REQUEST CHALLENGE: request a new challenge
     * @param $args
     * @param $settings
     * @return \Illuminate\Http\JsonResponse
     */
    public function request($args, $settings)
    {
        if ($args[0] === false) {
            $list = Storage::directories($settings['WORK_DIR'].'users/'.Auth::id().'/');

            //check if any directories are already present
            if (sizeof($list) > 0) {

                $sts = true;
                $msg = "You can request a new challenge only after completing the current challenge. \n";

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
                $new_level = Models\level::where('level', '=', $user_level['level'])->where('sub_level', '=', $user_level['sublevel'])->inRandomOrder()->first();
                $question_name = $new_level->name;
                $question_id = $new_level->id;
                $full_path = storage_path()."/app/".$settings['WORK_DIR'];
                //copy question to user directory -- will not work with $settings['WORK_DIR']
                shell_exec("cp -r ".$full_path."levels/".$question_name." ".$full_path."users/".Auth::id()."/".$question_name);
                //create solution.py file
                shell_exec("echo \"def main(n):\" > ".$full_path."users/".Auth::id()."/".$question_name."/solution.py");
                //update user table with new level id
                $user = Models\user::find(Auth::id());
                $user->level_id = $question_id;
                $user->save();
                //add code to start new countdown

                $sts = true;
                $msg = 'New challenge added.';
            }
        }
        else {
            $sts = false;
            $msg = 'request: too many arguments';
        }
        return response()->json(['STS' => $sts, 'MSG' => $msg]);
    }

    /**
     * STATUS: current level status of the user
     * @param $args
     * @param $settings
     * @return \Illuminate\Http\JsonResponse
     */
    public function status($args, $settings)
    {
        if ($args[0] === false) {
            $user_level = $this->getLevelData();

            $sts = true;
            $msg = "\n";

            /*//Testing purpose
                $user_level['level']=5;
                $user_level['max_level']=8;
                $user_level['max_sublevel']=8;
                $user_level['sublevel']=5;*/


            //for completed levels
            for ($i = 1; $i < $user_level['level']; $i++) {
                $msg = $msg.'[[;'.$settings['DIR_COLOR'].';]Level '.$i.' 100% [====================\]] ';
                $msg = "$msg\n";
            }

            //for partially completed levels
            $flag = true;
            $user_level['max_sublevel'] = ($user_level['max_sublevel'] == 0? 1: $user_level['max_sublevel']);
            for ($i = ($user_level['level'] == 0? 1: $user_level['level']); $i <= $user_level['max_level']; $i++) {
                $msg = $msg."Level ".$i." ".($i == $user_level['level']? round($user_level['sublevel']*100/$user_level['max_sublevel'])."%  ":"0%   ")."[";
                for ($j = 1; $j <= 20; $j++) {

                    if ($flag && $j == round($user_level['sublevel']*20/$user_level['max_sublevel']))
                        $flag=false;

                    if ($flag) {
                        $msg = $msg."=";
                    }
                    else
                        $msg = $msg.".";
                }
                $msg = $msg."]\n";
            }
        }
        else {
            $sts = false;
            $msg = 'status: too many arguments';
        }

        return response()->json(['STS' => $sts, 'MSG' => $msg]);
    }

    /**
     * SUBMIT THE SOLUTION: submit the solution. solution will be submitted only if all test cases are passed.
     * @param $args
     * @param $settings
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit($args, $settings)
    {
        if ($args[0] !==  false) {
            $output = $this->verify($args, $settings);
            $output = json_decode($output->content(), true);
            //check if verification was successful
            if ($output['STS'] == true) {
                $sts = true;
                $msg = 'Solution submitted successfully';
                $full_path = storage_path()."/app/".$settings['WORK_DIR'];
                $level_id = Models\user::find(Auth::id())->first()->level_id;
                $question_name = Models\level::find($level_id)->name;
                //remove questionfolder
                shell_exec("rm -r ".$full_path."users/".Auth::id()."/".$question_name);
                //change pwd
                Session::put('pwd', '~');
                //add code for removing countdown
            }
            else {
                $sts = false;
                $msg = 'Solution can be submitted only after successful verification';
            }
        }
        return response()->json(['STS' => true, 'MSG' => $msg]);
    }

    /**
     * VERIFY : the submitted solution will be executed and checked against a given set of test cases.
     * @param $args
     * @param $settings
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify($args, $settings)
    {
        if ($args[0] === false) {
            $level_id = Models\user::find(Auth::id())->first()->level_id;
            $question_name = Models\level::find($level_id)->name;
            $full_path = storage_path()."/app/".$settings['WORK_DIR'];
            //executing the code
            $output = shell_exec($full_path."answers/verify.sh "."solution.py ".$question_name." ".$full_path." ".Auth::id());
            $sts = false;
            $output_array = explode("\n", $output);
            //check the result of execution, if execution has failed or not
            if ($output_array[0] == 'FAIL') {
                $msg = "[[;#FF0000;]$output_array[1]]";
            }
            else {
                //successfull execution, no. of test cases satisfied
                if ($output_array[1] == '1111111111') {
                    $msg = "All test cases passed";
                    $sts = true;
                }
                else {
                    $msg = "\n";
                    //customizing color for each test case depending on pass/fail
                    for ($i = 1; $i <= 10; $i++ ) {
                        if ($output_array[1][$i-1] == 0) {
                            $msg = $msg."[[;#FF0000;]Test $i failed]\n";
                        }
                        else {
                            $msg = $msg."Test $i passed\n";
                        }
                    }
                }
            }

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
        $level_id = Models\user::find(Auth::id())->first()->level_id;
        $level_data = Models\level::find($level_id);
        $level = $level_data->level;
        $sublevel = $level_data->sub_level;
        $max_level = Models\level::orderBy('level', 'DESC')->first()->level;
        $max_sublevel = Models\level::where('level', '=', $level)->orderBy('sub_level', 'DESC')->first()->sub_level;
        return array('level' => $level, 'sublevel' => $sublevel, 'max_level' => $max_level, 'max_sublevel' => $max_sublevel);
    }

    /**
     * CHECK ARGUMENTS: validate arguments passed for a command
     * @param $req  request object
     * @return boolean true if correct number of arguments for the command else false
     */
    function check($req)
    {
        //write code to check each command and its arguments.
        switch ($req['method']) {
            case "help":
            case "logout":
            case "request":
            case "status":
                if (!isset($req['args']))
                    return true;
                break;
            case "ls":
            case "cd":
                if (!isset($req['args']) || sizeof($req['args']) <= 1)
                    return true;
                break;
            case "cat":
            case "verify":
            case "submit":
            case "edit":
                if (isset($req['args']) && sizeof($req['args']) == 1)
                    return true;
                break;
        }
        return false;
    }

    /**
     *Signout
     *
     * @param $args
     * @param $settings
     * @return JsonResponse
     */
    public function logout($args, $settings){
        if ($args[0] === false) {
            Auth::logout();
            Session::flush();
            return response()->json(['MSG' => 'logged out', 'STS' => true]);
        }
        return response()->json(['MSG' => 'invalid argument', 'STS' => false]);
    }


    public function  edit($args, $settings){

        $msg = 'No Such File';
        $sts = false;
        $file = false;

        if ( $args[0] !== false && !isset($args[1])) {
            if (strpos($args[0], 'solution') !== false) {
                //calculating present directory
                $user_dir = $settings['WORK_DIR'] . 'users/' . Auth::id() . '/';
                if (Session::get('pwd') !== '~') {
                    $user_dir = $user_dir . Session::get('pwd') . '/';
                }
                $user_dir = "$user_dir$args[0]";
                if (Storage::has($user_dir)) {
                    $msg = Storage::get($user_dir);
                    $file = $user_dir;
                    $sts = true;
                }
            }
            else {
                $msg = 'File not editable';
                $sts = false;
                $file = '';
            }
            //$msg = $user_dir;
        }

        return response()->json(['MSG' => $msg, 'STS' => $sts, 'FILE' => $file]);

    }

}