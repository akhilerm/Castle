<?php 
	require_once ("sessions.php");
	session_create();
	if (isset($_SESSION['SHADOW'])) {
	    header("location:shadow_mode.php");
    }
    else if (isset($_SESSION['GAMER'])) {
	    header("location:game.php");
    }
    else {
	    include("login_view.php");
    }

?>