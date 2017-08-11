<?php 
	require_once("sessions.php");
	session_create();
	if (isset($_SESSION['SHADOW'])) {
	    header("location:shadow_mode.php");
    }
    else if (isset($_SESSION['GAMER'])) {
	    header("location:gamer.php");
    }
    else {
	    header("location:login_view.php");
    }

?>