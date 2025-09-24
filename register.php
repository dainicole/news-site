<?php
require 'database.php';

//create CSRF token
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    $username = $_POST['username'];
    $inputtedPassword = $_POST['password'];

    //collect username to see if it exists
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($cnt);
    $stmt->fetch();
    $stmt->close();

    if ($cnt > 0) {
        $message = "Username already exists";
    } 
    else {
        //hashes password (latest recommended hashing algorithm - PASSWORD_DEFAULT)
        $password_hash = password_hash($inputtedPassword, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param('ss', $username, $password_hash);

        if ($stmt->execute()) {
            header("Location: homepage.php");
            exit;
        }
        else {
            $message = "Registration failed";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Page</title>
</head>
<body>
    <h1>Register</h1>

    <?php
    if ($message) {
        echo $message;
    }
    ?>

    <form action="register.php" method="post">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
        <input type="submit" value="Register">
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
    <p><a href="homepage.php">Back to stories</a></p>
</body>
</html>