<?php
	
	function cleanup ($str1,$con) {
		$str1=mysqli_real_escape_string($con,$str1);
		$str1=strip_tags($str1);
		$str1=addslashes($str1);
		return $str1;
	}

	function session_create() {
		session_start();
	}

	function session_set_gamer() {
		$_SESSION['GAMER']=1;
	}
	
	function session_set_shadow() {
		$_SESSION['SHADOW']=1;
	}

	function session_check() {
		if(isset($_SESSION['GAMER'])) {
			
			if ($_SESSION['GAMER']==1) {
				session_regenerate_id();
				return true;
			}	
			return false;
		}
		else if(isset($_SESSION['SHADOW'])) {
			if($_SESSION['SHADOW']==1) {
				session_regenerate_id();
				return true;
			}
			return false;
		}	
		else
			return false;
	}

	function sess_destroy() {
 		if (isset($_SESSION['SHADOW'])) {
 			unset($_SESSION['SHADOW']);
 		}
 		if (isset($_SESSION['GAMER'])) {
 			unset($_SESSION['GAMER']);
 		}	
 		session_destroy();
	}
?>