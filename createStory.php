<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //check login
    if (!isset($_SESSION['username'])) {
        echo "You must be logged in to add a story.";
        exit;
    }

    $username = $_SESSION['username'];
    $title = $_POST['story_title'];
    $content = $_POST['content'];
    $link = $_POST['link'];

    // example query: insert into employees (story_id, username, comment_text) values ('5', 'alice', 'great idea!')
    $stmt = $mysqli->prepare("insert into stories (username, title, content, link) values (?, ?, ?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('ssss', $username, $title, $content, $link);
    $stmt->execute();
    $stmt->close();

    header("Location: homepage.php");
    exit;
}

?>
