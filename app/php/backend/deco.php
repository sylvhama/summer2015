<?php 
	require_once("check.php");
	$_SESSION = array();
	session_destroy();
	header("location: admin.php");
?>