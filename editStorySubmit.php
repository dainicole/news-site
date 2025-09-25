<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //check login
    if (!isset($_SESSION['username'])) {
        echo "You must be logged in to edit a story.";
        exit;
    }

    $username = $_SESSION['username'];
    $story_id = (int) $_POST['story_id'];
    $author = $_POST['author'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $link = $_POST['link']; // TODO deal with if there's no link    

    // only edit if you're logged in as that user
    if ($author == $username) {
        // TODO make this an edit query     update employees set nickname='KJ' where id=2;
        $stmt = $mysqli->prepare("update stories set title=?, content=?, link=? where id=(?)");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('sssi', $title, $content, $link, $story_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: homepage.php");
    exit;
}
?>
