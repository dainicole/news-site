<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //check login
    if (!isset($_SESSION['username'])) {
        echo "You must be logged in to unlike a story.";
        exit;
    }

    $username = $_SESSION['username'];
    $story_id = (int)$_POST['story_id'];

    //query to get the likes
    $stmt = $mysqli->prepare("
        SELECT COUNT(*)
        FROM likes
        WHERE story_id=? AND username=?
    ");
    $stmt->bind_param("is", $story_id, $username);
    $stmt->execute();
    $stmt->bind_result($cnt);
    $stmt->fetch();
    $stmt->close();

    if ($cnt > 0) {
        //delete the user's like
        $delete_stmt = $mysqli->prepare("DELETE FROM likes WHERE story_id=? AND username=?");
        $delete_stmt->bind_param("is", $story_id, $username);
        $delete_stmt->execute();
        $delete_stmt->close();

        //decrement total likes in stories table
        //Cite: https://www.w3schools.com/sql/func_mysql_greatest.asp
        $update_stmt = $mysqli->prepare("UPDATE stories SET likes = GREATEST(likes - 1, 0) WHERE id=?");
        $update_stmt->bind_param("i", $story_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    header("Location: homepage.php");
    exit;
}
?>
