<?php
	include "config.php";
	$sql="DELETE FROM sales_items where sale_id='$_GET[slid]' and med_id='$_GET[mid]'";
	if ($conn->query($sql)){
	header("location:pharm-pos2.php");
	exit();
	}
	else
	echo "Error";
?>


