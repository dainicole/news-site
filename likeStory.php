<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    //check login
    if (!isset($_SESSION['username'])) {
        echo "You must be logged in to like a story.";
        exit;
    }

    $username = $_SESSION['username'];
    //FIEO - sanitize int
    $story_id = (int)$_POST['story_id'];
    $stmt = $mysqli->prepare("
        select count(*)
        from likes
        where story_id=? AND username=?
    ");
    $stmt->bind_param("is", $story_id, $username);
    $stmt->execute();
    $stmt->bind_result($cnt);
    $stmt->fetch();
    $stmt->close();
    if ($cnt == 0) {
        // Insert new like
        $insert_stmt = $mysqli->prepare("INSERT INTO likes (story_id, username) VALUES (?, ?)");
        $insert_stmt->bind_param("is", $story_id, $username);
        $insert_stmt->execute();
        $insert_stmt->close();

        // Increment total likes in stories table
        $update_stmt = $mysqli->prepare("UPDATE stories SET likes = likes + 1 WHERE id = ?");
        $update_stmt->bind_param("i", $story_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    header("Location: homepage.php");
    exit;
}

?>