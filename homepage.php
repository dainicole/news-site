<?php
require 'database.php';

//query to collect all stories
$stmt = $mysqli->prepare("
    SELECT stories.id, stories.title, stories.username, stories.link, stories.content
    FROM stories
    JOIN users ON stories.username = users.username
    ORDER BY stories.id DESC
");

if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->execute();
$stmt->bind_result($id, $title, $username, $link, $content);

$allStories = [];
//store table columns in respective variables
while ($stmt->fetch()) {
    $allStories[] = [
        'story_id' => $id,
        'title' => $title,
        'username' => $username,
        'link' => $link,
        'content' => $content,
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple News Site</title>
</head>
<body>
    <h1>Breaking News</h1>
    <p><a href="login.php">Login</a></p>
    <?php
        //display stories collected from query
        foreach ($allStories as $story) {
            echo "<h2>".htmlentities($story['title']) . "</h2>";
            echo "<p>".htmlentities($story['content'])."</p>";
            if (!empty($story['link'])) {
                echo "<p>Link: <a href='".htmlentities($story['link'])."'>".htmlentities($story['link'])."</a></p>";
            }
            echo "<p>Posted by: ".htmlentities($story['username'])."</p>";
                // query to collect all comments with story titles
                $stmt = $mysqli->prepare("
                SELECT username, comment_text
                FROM comments
                WHERE story_id = ?
                ORDER BY id DESC
            ");
            $stmt->bind_param("i", $story['story_id']);
            $stmt->execute();
            
            $stmt->bind_result($comment_user, $comment_text);

            echo "<h3>Comments:</h3>";
            $hasComments = false;
            while ($stmt->fetch()) {
                $hasComments = true;
                echo "<p>".htmlentities($comment_user).": ";
                echo htmlentities($comment_text)."</p>";
            }
            if (!$hasComments) {
                echo "<p>No comments yet.</p>";
            }
            echo '<form class="addCommentForm" action="createComment.php" method="POST">
                    <p>
                        <label for="addComment">Add a comment:</label>
                        <input type="text" name="new_comment_text" required>
                        <input type="hidden" name="token" value="'.htmlentities($_SESSION['token']).'" />                    </p>
                    <button type="submit">Submit</button>
                </form>';
        }
    ?>
    <form class="addStoryForm" action="createStory.php" method="POST">
        <p>
            <label for="stext" id="storytext">Add a story:</label>
            <input type="text" name="new_story_text" id="newStoryText" required>
        </p>
        <button type="submit">Submit</button>
    </form>'
</body>
</html>
