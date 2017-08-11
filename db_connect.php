<?php

    define('DB_HOST', 'localhost');
    define('DB_NAME', 'foobar');
    define('DB_USER','phpmyadmin');
    define('DB_PASSWORD','awsdrift');
    $con=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysqli_error());
	
?>
