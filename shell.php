<?php 
	require ("json-rpc.php");
	include ("config.php");
	require_once ("db_connect.php");

	class Shell_Commands {

		private $COMMAND_COLOR;
		private $DIR_COLOR;

		public function __construct() {
			global $COMMAND_COLOR, $DIR_COLOR;
			$this->COMMAND_COLOR = $COMMAND_COLOR;
			$this->DIR_COLOR = $DIR_COLOR;
		}

		public function cd($args) {

		}

		public function cat($args) {

		}

		public function edit($args) {

		}

		public function help($token) {
			if ($this->check("help", $token)) {
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

		public function ls($token) {
			$list[]=$this->COMMAND_COLOR;
			return $list;
		}

		public function logout($token) {
            if ($this->check("logout", $token)) {
                session_create();
                if (session_check()) {
                    sess_destroy();
                    header("location:index.php");
                }
                else{
                    header("location:index.php");
                }
            }
		}

		public function request($token) {
			
		}

		public function status($token) {
			
		}

		public function submit($token) {
			
		}

		public function verify($token) {
			
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