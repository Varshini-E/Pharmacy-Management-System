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

	$medicineId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	if ($medicineId === false || $medicineId === null || $medicineId <= 0) {
		header("location:inventory-view.php");
		exit();
	}

	$stmt = $conn->prepare("DELETE FROM meds WHERE med_id = ?");
	if ($stmt) {
		$stmt->bind_param("i", $medicineId);
		$stmt->execute();
		$stmt->close();
	}

	header("location:inventory-view.php");
	exit();
?>