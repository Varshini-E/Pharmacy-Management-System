<?php
	include "config.php";
	session_start();
	unset($_SESSION["user"]);
	 if(session_destroy()) {
	header("Location:mainpage1.php");
	}
?>