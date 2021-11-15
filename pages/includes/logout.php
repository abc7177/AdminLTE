<?php
    include("../../docs/_includes/connection.php");
    session_destroy();
    echo '<script>localStorage.setItem("LogoutSuccess",1)</script>';	// Login Failed flag.
	echo "<script>window.location='../login.php'</script>";
	exit;
?>

