<?php
	include "config.php";
	$sql="DELETE FROM employee where e_id='$_GET[id]'";
	if ($conn->query($sql))
	header("location:employee-view.php");
	else
	echo "error";
?>