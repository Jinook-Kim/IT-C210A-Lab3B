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

if(!isset($_SESSION['id'])) {
    header('Location: views/login.php');
    exit;
}

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

$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("d", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>To Do List</title>

  <!-- Links to stylesheets -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <link rel='icon' href='favicon.ico' type='image/x-icon' sizes="16x16" />
</head>

<body onload="readStorage()">
  <nav class="navbar navbar-expand-lg">
    <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse me-auto" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="goolge.com">Google</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="goolge.com">ChatGPT</a>
            </li>
        </ul>
    </div>

    <div>
        Welcome <?php echo $user['username']; ?> |
    </div>
    <a href="actions/logout_action.php">&#160 sign out</a>
  </nav>

  <h1>Tasks</h1>
  
  <input type='checkbox' class="toggle-switch" id="cb-sort"/><label for="cb-sort">Sort by date</label>
  <input type='checkbox' class="toggle-switch" id="cb-filter"/><label for="cb-sort">Filter completed tasks</label>
  <ul class="tasklist" id="list">
    <?php
      $user_id = $_SESSION['id'];

      $stmt = $conn->prepare("SELECT id, text, date, done FROM task WHERE user_id = ?");
      $stmt->bind_param('i', $user_id);
      $stmt->execute();
      $stmt->bind_result($id, $text, $date, $done);

      while ($stmt->fetch()) {
        echo '<li class="task" style="display: flex; align-items: center;">';
        echo '<form action="actions/update_action.php" method="post" style="display: inline-block;">';
        if ($done) {
          echo '<input type="checkbox" class="task-done checkbox-icon" id="task-complete" name="task-complete" value="'.$id.'" checked onchange="this.form.submit()"/>';
        } else {
          echo '<input type="checkbox" class="task-done checkbox-icon" id="task-complete" name="task-complete" value="'.$id.'" onchange="this.form.submit()"/>';
        }
        echo '<input type="hidden" name="task-id" value="'.$id.'">';
        echo '</form>';
        if ($done) {
          echo '<span class="task-description" style="margin: 0 10px; text-decoration: line-through;">' . $text . '</span>';
        } else {
          echo '<span class="task-description" style="margin: 0 10px;">' . $text . '</span>';
        }
        $formatted_date = date('m/d/Y', strtotime($date));
        echo '<span class="task-date" style="margin: 0 10px;">' . $formatted_date . '</span>';
        echo '<form action="actions/delete_action.php" method="post" style="display: inline-block;">';
        echo '<input type="hidden" name="task-id" value="'.$id.'">';
        echo '<button type="submit" class="task-delete material-icon">delete_forever</button>';
        echo '</form>';
        echo '</li>';
      }

      $stmt->close();
      $conn->close();
    ?>
  </ul>

  <form class="form-create-task" id="taskForm" action="actions/create_action.php" method="post">
    <input type="text" name="description" placeholder="New Task" class="my-description" id="taskText" required/>
    <br/>
    <input type="date" name="date" id="taskDate" required/>
    <br/>
    <button type="submit" class="create-button">Create Task</button>
  </form>

  <script src="js/script.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>
