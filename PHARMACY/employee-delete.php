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

	$employeeId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	if ($employeeId === false || $employeeId === null || $employeeId <= 0) {
		header("location:employee-view.php");
		exit();
	}

	$stmt = $conn->prepare("DELETE FROM employee WHERE e_id = ?");
	if ($stmt) {
		$stmt->bind_param("i", $employeeId);
		$stmt->execute();
		$stmt->close();
	}

	header("location:employee-view.php");
	exit();
?>