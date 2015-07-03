<?php
	session_start();

	require_once("./config.php");

	if(!isset($_SESSION['_login']) || !isset($_SESSION['_pass']))
	{
		require_once("./conec.html");
		exit();
	}
	else
	{
		if(($_admin_login != $_SESSION['_login']) || ($_SESSION['_pass'] != $_admin_pass))
		{
			require_once("./conec.html");
			exit();
		}
	}
?>