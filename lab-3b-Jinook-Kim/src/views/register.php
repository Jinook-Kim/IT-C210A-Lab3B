<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="../css/register_style.css">
</head>
<body>
    <div class="container">
        <form action="../actions/register_action.php" method="post">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Register">
        </form>
        <a href="login.php">Sign in</a>
    </div>
</body>
</html>