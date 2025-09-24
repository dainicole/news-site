<?php
require 'database.php';

//print_r($_SESSION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

$username = $_SESSION['username'];

$text = $_POST['new_comment_text'];
$story_id = $_POST['affiliated_story'];

// example query: insert into employees (story_id, username, comment_text) values ('5', 'alice', 'great idea!')
$stmt = $mysqli->prepare("insert into comments (story_id, username, comment_text) values (?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('iss', $story_id, $username, $text);

$stmt->execute();

$stmt->close();

}

?>