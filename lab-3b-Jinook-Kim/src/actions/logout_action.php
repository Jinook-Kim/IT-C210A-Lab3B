<?php
// ./actions/logout_action.php

// Read variables and create connection
$mysql_servername = getenv("MYSQL_SERVERNAME");
$mysql_user = getenv("MYSQL_USER");
$mysql_password = getenv("MYSQL_PASSWORD");
$mysql_database = getenv("MYSQL_DATABASE");
$conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// TODO: Log the user out
session_start();
if(isset($_SESSION['id'])) {
	$sql = "UPDATE user SET logged_in = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d", $_SESSION['id']);
    $stmt->execute();

	unset($_SESSION['id']);
}

header("Location: ../index.php");
die;
?>
