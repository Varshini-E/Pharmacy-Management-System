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

	$purchaseId = filter_input(INPUT_GET, 'pid', FILTER_VALIDATE_INT);
	$supplierId = filter_input(INPUT_GET, 'sid', FILTER_VALIDATE_INT);
	$medicineId = filter_input(INPUT_GET, 'mid', FILTER_VALIDATE_INT);
	if ($purchaseId === false || $purchaseId === null || $purchaseId <= 0 || $supplierId === false || $supplierId === null || $supplierId <= 0 || $medicineId === false || $medicineId === null || $medicineId <= 0) {
		header("location:purchase-view.php");
		exit();
	}

	$stmt = $conn->prepare("DELETE FROM purchase WHERE p_id = ? AND sup_id = ? AND med_id = ?");
	if (!$stmt) {
		header("location:purchase-view.php");
		exit();
	}
	$stmt->bind_param("iii", $purchaseId, $supplierId, $medicineId);
	if ($stmt->execute()) {
		header("location:purchase-view.php");
	} else {
		echo "Error deleting record.";
	}
	$stmt->close();
	exit();
?>