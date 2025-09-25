<?php
require 'database.php';

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

//query to collect all stories
$stmt = $mysqli->prepare("
    SELECT stories.id, stories.title, stories.username, stories.link, stories.content, stories.likes
    FROM stories
    JOIN users ON stories.username = users.username
    ORDER BY stories.id DESC
");

if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->execute();
$stmt->bind_result($id, $title, $username, $link, $content, $likes);

$allStories = [];
//store table columns in respective variables
while ($stmt->fetch()) {
    $allStories[] = [
        'story_id' => $id,
        'title' => $title,
        'username' => $username,
        'link' => $link,
        'content' => $content,
        'likes' => $likes,
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
    <?php
        if (isset($_SESSION['username'])) {
            echo "<p>Hi " . htmlentities($_SESSION['username']) . "!</p>";
            echo '<p><a href="logout.php">Logout</a></p>';
        } else {
            echo '<p><a href="login.php">Login</a> to add a story or comment.</p>';
        }
        //display stories collected from query
        foreach ($allStories as $story) {
            echo "<h2>".htmlentities($story['title']) . "</h2>";
            echo "<p>".htmlentities($story['content'])."</p>";
            if (!empty($story['link'])) {
                echo "<p>Link: <a href='".htmlentities($story['link'])."'>".htmlentities($story['link'])."</a></p>";
            }
            echo "<p>Likes: ".htmlentities($story['likes'])."</p>";
            echo '<form action="likeStory.php" method="POST" class="likeForm">
                <p>
                    <input type="hidden" name="story_id" value="'.$story['story_id'].'" />
                    <input type="hidden" name="author" value="'.$story['username'].'" />
                    <input type="hidden" name="token" value="'.$_SESSION['token'].'" />
                </p>
                <button type="submit">Like</button>
                    </form>';
            echo "<p>Posted by: ".htmlentities($story['username'])."</p>";
            // if you're the poster, show edit/delete buttons for the story
                if($story['username'] == $_SESSION['username']) {
                    // edit button
                    echo '<form action="editStoryPage.php" method="POST" class="editForm">
                        <p>
                            <input type="hidden" name="story_id" value="'.$story['story_id'].'" />
                            <input type="hidden" name="author" value="'.$story['username'].'" />
                            <input type="hidden" name="title" value="'.$story['title'].'" />
                            <input type="hidden" name="content" value="'.$story['content'].'" />
                            <input type="hidden" name="link" value="'.$story['link'].'" />
                            <input type="hidden" name="token" value="'.$_SESSION['token'].'" />
                        </p>
                        <button type="submit">Edit</button>
                    </form>';
                    // delete button
                    echo '<form action="deleteStory.php" method="POST" class="deleteForm">
                        <p>
                            <input type="hidden" name="story_id" value="'.$story['story_id'].'" />
                            <input type="hidden" name="author" value="'.$story['username'].'" />
                            <input type="hidden" name="token" value="'.$_SESSION['token'].'" />
                        </p>
                        <button type="submit">Delete</button>
                    </form>';
                }



            //query to collect all comments that correspond to a given story
            $cstmt = $mysqli->prepare("
            SELECT username, comment_text, id
            FROM comments
            WHERE story_id = ?
            ORDER BY id DESC
            ");
            $cstmt->bind_param("i", $story['story_id']);
            $cstmt->execute();
            $cstmt->bind_result($comment_user, $comment_text, $comment_id);

            echo "<h3>Comments:</h3>";
            $hasComments = false;
            while ($cstmt->fetch()) {  
                $hasComments = true;
                echo "<p>".htmlentities($comment_user).": ";
                echo htmlentities($comment_text)."</p>";

                // if you're the poster, show edit/delete buttons for the comment
                if($comment_user == $_SESSION['username']) {
                    // edit button
                    echo '<form action="editComment.php" method="POST" class="editForm">
                        <p>
                            <input type="hidden" name="comment_id" value="'.$comment_id.'" />
                            <input type="hidden" name="author" value="'.$comment_user.'" />
                            <input type="hidden" name="token" value="'.$_SESSION['token'].'" />
                        </p>
                        <button type="submit">Edit</button>
                    </form>';
                    // delete button
                    echo '<form action="deleteComment.php" method="POST" class="deleteForm">
                        <p>
                            <input type="hidden" name="comment_id" value="'.$comment_id.'" />
                            <input type="hidden" name="author" value="'.$comment_user.'" />
                            <input type="hidden" name="token" value="'.$_SESSION['token'].'" />
                        </p>
                        <button type="submit">Delete</button>
                    </form>';
                }
            }
            $cstmt->close();
            if (!$hasComments) {
                echo "<p>No comments yet.</p>";
            }
            echo '<form class="addCommentForm" action="createComment.php" method="POST">
                <p>
                    <label for="addComment'.$story['story_id'].'">Add a comment:</label>
                    <input type="text" id="addComment'.$story['story_id'].'" name="new_comment_text" required>
                    <input type="hidden" name="story_id" value="'.$story['story_id'].'" />
                    <input type="hidden" name="token" value="'.$_SESSION['token'].'" />
                </p>
                <button type="submit">Submit</button>
            </form>';
        }
    ?>
    <h3>Add a Story:</h3>
    <form class="addStoryForm" action="createStory.php" method="POST">
        <p>
            <label for="newStoryTitle">Title:</label>
            <input type="text" name="story_title" id="newStoryTitle" required>
        </p>
        <p>
            <label for="newStoryContent">Content:</label>
            <textarea name="content" id="newStoryContent" rows="4" required></textarea>
        </p>
        <p>
            <label for="newStoryLink">Link (optional):</label>
            <input type="text" name="link" id="newStoryLink">
        </p>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
        <button type="submit">Submit</button>
    </form>
</body>
</html>