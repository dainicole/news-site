<?php
require 'database.php';

//print_r($_SESSION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //check login
    if (!isset($_SESSION['username'])) {
        echo "You must be logged in to add a comment.";
        exit;
    }

$username = $_SESSION['username'];
$text = $_POST['new_comment_text'];
$story_id = $_POST['story_id'];

// example query: insert into employees (story_id, username, comment_text) values ('5', 'alice', 'great idea!')
$stmt = $mysqli->prepare("insert into comments (story_id, username, comment_text) values (?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('iss', $story_id, $username, $text);
$stmt->execute();
$stmt->close();

header("Location: homepage.php");
exit;
}

?>
