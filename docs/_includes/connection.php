<?php
	session_start();
	
	// configuration
	set_time_limit(0);
	ini_set('memory_limit','4096M');
	error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
	date_default_timezone_set("Asia/Singapore");

	// connection
	$host = "localhost";
	$user = "root";
	$pass = "MLAspire5740#";
	$db_n = "itac_beauty";
				
	$con = mysqli_connect($host,$user,$pass,$db_n);
	mysqli_query($con,"SET character_set_connection = 'utf8'");
	mysqli_query($con,"SET character_set_client= 'utf8'");
	mysqli_query($con,"SET character_set_results= 'utf8'");

    // Check connection
	if (!$con) {
		die("Connection failed: " . mysqli_connect_error());
	} else {
		//echo "Connected successfully";
	}
?>