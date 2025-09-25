<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //check login
    if (!isset($_SESSION['username'])) {
        echo "You must be logged in to edit stories.";
        exit;
    }

    $username = $_SESSION['username'];
    $story_id = (int)$_POST['story_id'];
    $author = $_POST['author'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $link = $_POST['link'];
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
    <form class="addStoryForm" action="editStorySubmit.php" method="POST">
        <p>
            <label for="storyTitle">Title:</label>
            <textarea name="title" id="storyTitle" rows="4" required><?php echo htmlentities($title) ?></textarea>
        </p>
        <p>
            <label for="storyContent">Content:</label>
            <textarea name="content" id="storyContent" rows="4" required><?php echo htmlentities($content) ?></textarea>
        </p>
        <p>
            <label for="storyLink">Link (optional):</label>
            <input type="text" name="link" id="storyLink" value=<?php echo htmlentities($link) ?>>
        </p>
        <input type="hidden" name="story_id" value="<?php echo $story_id; ?>" />
        <input type="hidden" name="author" value="<?php echo  $author; ?>" />
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
        <button type="submit">Update</button>
    </form>
</body>
</html>