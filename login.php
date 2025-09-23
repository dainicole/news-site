<?php
require 'database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $inputtedPassword = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE username=?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($cnt, $user_id, $pwd_hash);
    $stmt->fetch();

    //check that user exists and psasword is correct
    if ($cnt == 1 && password_verify($inputtedPassword, $pwd_hash)) {
        $_SESSION['user_id'] = $user_id;
        header("Location: homepage.php");
        exit;
    } else {
        $message = "Login Failed";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
</head>
<body>
    <h1>Login</h1>

    <?php
    if ($message) {
        echo $message;
    }
    ?>

    <form action="login.php" method="post">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
