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

	$customerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	if ($customerId === false || $customerId === null || $customerId <= 0) {
		header("location:customer-view.php");
		exit();
	}

	$stmt = $conn->prepare("DELETE FROM customer WHERE c_id = ?");
	if ($stmt) {
		$stmt->bind_param("i", $customerId);
		$stmt->execute();
		$stmt->close();
	}

	header("location:customer-view.php");
	exit();
?>