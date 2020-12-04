<?php
	include "config.php";
	$sql="DELETE FROM customer where c_id='$_GET[id]'";
	if ($conn->query($sql))
	header("location:customer-view.php");
	else
	echo "error";
?>