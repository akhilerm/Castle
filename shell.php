<?php 
	require ("json-rpc.php");
	include ("config.php");
	require_once ("db_connect.php");
	require_once ("sessions.php");

	session_create();

	class Shell_Commands {

		private $COMMAND_COLOR;
		private $DIR_COLOR;
		private $WORK_DIR;
		private $con;

		public function __construct() {
			global $COMMAND_COLOR, $DIR_COLOR, $WORK_DIR, $con;
			$this->COMMAND_COLOR = $COMMAND_COLOR;
			$this->DIR_COLOR = $DIR_COLOR;
			$this->WORK_DIR = $WORK_DIR;
			$this->con = $con;
		}

		public function cd($args) {
            if ($this->check("cd", $args)) {
                if ($args[0] == ".."){
                    $_SESSION['PWD'] = '~';
                }
                else if ($args[0] != "."){
                    $dir = $this->WORK_DIR.'/users/'.$_SESSION['USER_ID'].'/';
                    if ($_SESSION['PWD']!='~') {
                        $dir = $dir.explode("/", $_SESSION['PWD'])[1].'/';
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
            }


		}

		public function cat($args) {
            if ($this->check("cat", $args)) {
                $dir = $this->WORK_DIR.'/users/'.$_SESSION['USER_ID'].'/';
                if ($_SESSION['PWD']!='~') {
                    $dir = $dir.explode("/", $_SESSION['PWD'])[1].'/';
                }
                $files = preg_grep('/^([^.])/', scandir($dir));
                foreach ($files as $file) {
                    if (is_file($dir.$file) && $file == $args[0]) {
                        $result[] = file_get_contents($dir.$file);
                        return $result;
                    }
                }
                $result[] = "cat: ".$args[0].": No such file";
                return $result;
            }
		}

		public function edit($args) {
            //inbuilt editor needed
		}

		public function help($args) {
			if ($this->check("help", $args)) {
				$result[]="\nUse the following shell commands:
[[;".$this->COMMAND_COLOR.";]cd]     - change directory [dir_name]
[[;".$this->COMMAND_COLOR.";]cat]    - print file [file_name]
[[;".$this->COMMAND_COLOR.";]clear]  - clear the terminal
[[;".$this->COMMAND_COLOR.";]edit]   - open file in editor [file_name]
[[;".$this->COMMAND_COLOR.";]ls]     - list directory contents [dir_name]
[[;".$this->COMMAND_COLOR.";]logout] - logout from Castle
[[;".$this->COMMAND_COLOR.";]request]- request a new challenge
[[;".$this->COMMAND_COLOR.";]status] - print progress
[[;".$this->COMMAND_COLOR.";]submit] - submit final solution for assessment [file_name]
[[;".$this->COMMAND_COLOR.";]verify] - runs tests on solution file [file_name]\n";
			    return $result;
			}
		}

		public function ls($args) {
			if ($this->check("ls", $args)) {
			    $dir = $this->WORK_DIR.'/users/'.$_SESSION['USER_ID'].'/';
                if ($_SESSION['PWD']!='~') {
                    $dir = $dir.explode("/", $_SESSION['PWD'])[1].'/';
                }
                $files = preg_grep('/^([^.])/', scandir($dir));
                $result[]="\n";
                foreach ($files as $file) {
                    if (is_dir($dir.$file)) {
                        $result[0] = $result[0]."[[;".$this->DIR_COLOR.";]".$file."/]\n";
                    }
                    else
                        $result[0] = $result[0].$file."\n";
                }
            }
			return $result;
		}

		public function logout($args) {
            if ($this->check("logout", $args)) {
                session_create();
                if (session_check()) {
                    sess_destroy();
                    header("location:index.php");
                    return;
                }
                else{
                    header("location:index.php");
                    return;
                }
            }
            $result[] = "Logging out...";
            return $result;
		}

		public function request($args) {
			if ($this->check("request", $args)) {
                $dir = $this->WORK_DIR.'/users/'.$_SESSION['USER_ID'].'/';
                if ($_SESSION['PWD']!='~') {
                    $dir = $dir.explode("/", $_SESSION['PWD'])[1].'/';
                }
                $files = preg_grep('/^([^.])/', scandir($dir));
                $folder = "NIL";
                foreach ($files as $file) {
                    if (is_dir($dir.$file)) {
                        $folder = $file;
                    }
                }
                if ($folder!="NIL") {
                    $result[] = "You can request a new challenge only after completing the current challenge\n";
                    return $result;
                }
                else {
                    //redundant code ahead. use a function
                    $rows = $this->con->query("select level_no, sublevel_no from levels,users where levels.id=level_id and users.id=".$_SESSION['USER_ID']);
                    $row = $rows->fetch_assoc();
                    $level = $row['level_no'];
                    $sublevel = $row['sublevel_no'];
                    ///To check whether player has completed the game
                    $rows=$this->con->query("select level_no, sublevel_no from levels order by level_no desc, sublevel_no desc");
                    $row = $rows->fetch_assoc();
                    $max_level = $row['level_no'];
                    $max_sublevel = $row['sublevel_no'];
                    //to find maximum sublevel of current level
                    if ($level == $max_level) {
                        $cur_max_sublevel = $max_sublevel;
                    }
                    else{
                        while($row=$rows->fetch_assoc()) {
                            if ($level == $row['level_no']){
                                $cur_max_sublevel = $row['sublevel_no'];
                                break;
                            }
                        }
                    }
                    //redundant code ^
                    //checking whether game over
                    if ($level == $max_level && $sublevel == $max_sublevel) {
                        $result[] = "No more challenges. You did it";
                        return $result;
                    }
                    else if ($sublevel == $max_sublevel) {
                        $sublevel = 1;
                        $level = $level+1;
                    }
                    else{
                        $sublevel = $sublevel+1;
                        if($level == 0) {
                            $level++;
                        }
                    }
                    //here, add code to start new countdown
                    $rows=$this->con->query("select id, name from levels where level_no=".$level." and sublevel_no=".$sublevel." order by rand() limit 1");
                    $row=$rows->fetch_assoc();
                    $qs_name=$row['name'];
                    $qs_id=$row['id'];
                    $output=shell_exec("cp -r ".$this->WORK_DIR."levels/".$qs_name." ".$this->WORK_DIR."users/".$_SESSION['USER_ID']."/".$qs_name);
                    $this->con->query("update users set level_id=".$qs_id." where id=".$_SESSION['USER_ID']);
                    //$result[]="New challenge [[;".$this->DIR_COLOR.";]".$qs_name."]/ added\n";
                    $result[]=is_writable($this->WORK_DIR."users");
                    return $result;
                }
            }
		}

		public function status($args) {
			if ($this->check("status", $args)) {
                //redundant code ahead. use a function instead
                $rows = $this->con->query("select level_no, sublevel_no from levels,users where levels.id=level_id and users.id=".$_SESSION['USER_ID']);
                $row = $rows->fetch_assoc();
                $level = $row['level_no'];
                $sublevel = $row['sublevel_no'];
                ///To check whether player has completed the game
                $rows=$this->con->query("select level_no, sublevel_no from levels order by level_no desc, sublevel_no desc");
                $row = $rows->fetch_assoc();
                $max_level = $row['level_no'];
                $max_sublevel = $row['sublevel_no'];
                //to find maximum sublevel of current level
                if ($level == $max_level) {
                    $cur_max_sublevel = $max_sublevel;
                }
                else{
                    while($row=$rows->fetch_assoc()) {
                        if ($level == $row['level_no']){
                            $cur_max_sublevel = $row['sublevel_no'];
                            break;
                        }
                    }
                }
                //redundant code ends here
                /* For TEsting purpose
                 * $level=5;
                $max_level=8;
                $cur_max_sublevel=8;
                $sublevel=5;*/
                $result[0]="\n";
                //for completed levels
                for ($i=1;$i<$level;$i++) {
                    $result[0]=$result[0]."[[;".$this->DIR_COLOR.";]Level ".$i." 100% [===================='\]']\n";

                }
                $flag=true;
                for ($i=($level==0?1:$level);$i<=$max_level;$i++) {
                    $result[0]=$result[0]."Level ".$i." ".($i==$level?round($sublevel*100/$cur_max_sublevel)."%  ":"0%   ")."[";
                    for ($j=1;$j<=20;$j++) {
                        if ($flag && $j==round($sublevel*20/$cur_max_sublevel)) $flag=false;
                        if ($flag) {
                            $result[0]=$result[0]."=";
                        }
                        else
                            $result[0]=$result[0].".";
                    }
                    $result[0]=$result[0]."]\n";
                }
                return $result;
            }
		}

		public function submit($args) {
			
		}

		public function verify($args) {
			
		}

		//perform check. return true if argument number matches for the command
		function check($command, $args) {
			/*if (in_array($command, ['cd', 'cat']) )
				return true;*/
			return true;
		}
	}
	handle_json_rpc(new Shell_Commands());
?>