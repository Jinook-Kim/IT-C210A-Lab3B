<?php
error_reporting(-1);
session_start();
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

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO task (text, date, user_id) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $description, $date, $user_id);

// Set parameters and execute
$description = htmlentities($_POST["description"]);
$date = htmlentities($_POST["date"]);
$user_id = $_SESSION['id'];
$stmt->execute();

// Close statement and connection
$stmt->close();
$conn->close();

header("Location: ../index.php");
die();
?>