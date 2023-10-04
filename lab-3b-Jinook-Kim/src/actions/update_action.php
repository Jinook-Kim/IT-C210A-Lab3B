<?php
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task-id'];
    $task_complete = isset($_POST['task-complete']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE task SET done = ? WHERE id = ?");
    $stmt->bind_param('ii', $task_complete, $task_id);

    if ($stmt->execute()) {
        header('Location: index.php');
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

header("Location: ../index.php");
?>