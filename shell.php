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

		public function __construct() {
			global $COMMAND_COLOR, $DIR_COLOR, $WORK_DIR;
			$this->COMMAND_COLOR = $COMMAND_COLOR;
			$this->DIR_COLOR = $DIR_COLOR;
			$this->WORK_DIR = $WORK_DIR;
		}

		public function cd($args) {
            if ($this->check("cd", $args)) {
                if ($args[0] == ".."){
                    $_SESSION['PWD'] = '~';
                }
                else if ($args[0] != "."){
                    $dir = $this->WORK_DIR.'/'.$_SESSION['USER_ID'].'/';
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
                        $result[0] = "\n[[;".$this->COMMAND_COLOR.";]cd]: ".$args[0].": Not a directory\n";
                        $result[1] = "false";
                        return $result;
                    }

                }

                $result[0]=$_SESSION['USER_NAME'].'@Castle:'.$_SESSION['PWD'].'/$';
                $result[1]="true";
                return $result;
            }


		}

		public function cat($args) {

		}

		public function edit($args) {

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
			}
			return $result;
		}

		public function ls($args) {
			if ($this->check("ls", $args)) {
			    $dir = $this->WORK_DIR.'/'.$_SESSION['USER_ID'].'/';
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
			
		}

		public function status($args) {
			
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