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

	$supplierId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	if ($supplierId === false || $supplierId === null || $supplierId <= 0) {
		header("location:supplier-view.php");
		exit();
	}

	$stmt = $conn->prepare("DELETE FROM suppliers WHERE sup_id = ?");
	if ($stmt) {
		$stmt->bind_param("i", $supplierId);
		$stmt->execute();
		$stmt->close();
	}

	header("location:supplier-view.php");
	exit();
?>