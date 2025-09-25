<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //check login
    if (!isset($_SESSION['username'])) {
        echo "You must be logged in to delete stories.";
        exit;
    }

    $username = $_SESSION['username'];
    $story_id = $_POST['story_id'];
    $author = $_POST['author'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $link = $_POST['link']; // TODO deal with if there's no link

    // only edit if you're logged in as that user
    if ($author == $username) {
        // TODO make this an edit query
        //$stmt = $mysqli->prepare("delete from stories where id=(?)");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $story_id);
        $stmt->execute();
        $stmt->close();
                
        header("Location: homepage.php");
        exit;
    }
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
    <form class="addStoryForm" action="editStory.php" method="POST">
        <p>
            <label for="storyTitle">Title:</label>
            <input type="text" name="story_title" id="storyTitle" value=<?php htmlentities($title) ?> required>
        </p>
        <p>
            <label for="storyContent">Content:</label>
            <textarea name="content" id="storyContent" value=<?php htmlentities($content) ?> rows="4" required></textarea>
        </p>
        <p>
            <label for="storyLink">Link (optional):</label>
            <input type="text" name="link" id="storyLink" value=<?php htmlentities($link) ?>>
        </p>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
        <button type="submit">Update</button>
    </form>
</body>
</html>