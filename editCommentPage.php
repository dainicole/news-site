<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //check login
    if (!isset($_SESSION['username'])) {
        echo "You must be logged in to edit comments.";
        exit;
    }

    $username = $_SESSION['username'];
    $comment_id = (int)$_POST['comment_id'];
    $author = $_POST['author'];
    $content = $_POST['content'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple News Site</title>
</head>
<body>
    <h1>Edit</h1>
    <form class="editCommentForm" action="editCommentSubmit.php" method="POST">
        <p>
            <label for="commentContent">Content:</label>
            <textarea name="content" id="commentContent" rows="4" required><?php echo htmlentities($content) ?></textarea>
        </p>
        <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>" />
        <input type="hidden" name="author" value="<?php echo  $author; ?>" />
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
        <button type="submit">Update</button>
    </form>
</body>
</html>