<?php
// ./actions/register_action.php

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

// TODO: Register a new user
// $username = $_POST['username'];
// $password = $_POST['password'];
$username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
$password = htmlentities($_POST['password'], ENT_QUOTES, 'UTF-8');

// Check if username already exists
$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
	echo '<script type="text/javascript">
        var result = confirm("Choose a different username");
        if (result) {
            window.location.href = "../views/register.php";
        } else {
			window.location.href = "../views/login.php";
		}
    	</script>';
} else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();

    ?>
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body {
                    background-color: #f0f0f0;
                    font-family: Arial, sans-serif;
                }
                .container {
                    width: 60%;
                    margin: 0 auto;
                    padding: 50px;
                    background-color: #fff;
                    border-radius: 5px;
                    box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
                    text-align: center;
                }
                h1 {
                    color: #4CAF50;
                }
                p {
                    color: #666;
                    font-size: 1.2em;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Registration Successful</h1>
                <p>Click <a href="../index.php">here</a> to sign in</p>
            </div>
        </body>
        </html>
    <?php
}

$stmt->close();
$conn->close();
?>
