<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //check login
    if (!isset($_SESSION['username'])) {
        echo "You must be logged in to delete comments.";
        exit;
    }

    $username = $_SESSION['username'];
    $comment_id = $_POST['comment_id'];
    $author = $_POST['author'];

    // only delete if you're logged in as that user
    if ($author == $username) {
        $stmt = $mysqli->prepare("delete from comments where id=(?)");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $comment_id);
        $stmt->execute();
        $stmt->close(); 
    }
   
}

header("Location: homepage.php");
exit;
?>
