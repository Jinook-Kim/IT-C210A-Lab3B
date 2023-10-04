<?php
// ./actions/login_action.php

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

// TODO: Log the user in
// $username = $_POST['username'];
// $password = $_POST['password'];
$username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
$password = htmlentities($_POST['password'], ENT_QUOTES, 'UTF-8');

$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
	session_start();
    $_SESSION['id'] = $user['id'];

    $sql = "UPDATE user SET logged_in = 1 WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    header("Location: ../index.php");
} else {
    echo '<script type="text/javascript">
        var result = confirm("Invalid username or password. \nOK to retry. Cancel to register.");
        if (!result) {
            window.location.href = "../views/register.php";
        } else {
			window.location.href = "../views/login.php";
		}
    	</script>';
}

$stmt->close();
$conn->close();
?>