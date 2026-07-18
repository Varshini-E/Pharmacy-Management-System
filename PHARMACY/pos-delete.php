<?php
	session_start();
	if (!isset($_SESSION['user']) || !is_string($_SESSION['user']) || $_SESSION['user'] === '') {
		header("location:mainpage.php");
		exit();
	}

	include "config.php";

	$authStmt = $conn->prepare("SELECT 1 FROM admin WHERE a_username = ? LIMIT 1");
	if (!$authStmt) {
		header("location:mainpage.php");
		exit();
	}
	$authStmt->bind_param("s", $_SESSION['user']);
	$authStmt->execute();
	$authStmt->store_result();
	if ($authStmt->num_rows !== 1) {
		$authStmt->close();
		header("location:mainpage.php");
		exit();
	}
	$authStmt->close();

	$saleId = filter_input(INPUT_GET, 'slid', FILTER_VALIDATE_INT);
	$medicineId = filter_input(INPUT_GET, 'mid', FILTER_VALIDATE_INT);
	if ($saleId === false || $saleId === null || $saleId <= 0 || $medicineId === false || $medicineId === null || $medicineId <= 0) {
		header("location:pos2.php");
		exit();
	}

	$stmt = $conn->prepare("DELETE FROM sales_items WHERE sale_id = ? AND med_id = ?");
	if ($stmt) {
		$stmt->bind_param("ii", $saleId, $medicineId);
		$stmt->execute();
		$stmt->close();
	}

	header("location:pos2.php");
	exit();
?>

