<?php
session_start();

$mysqli = new mysqli('localhost', 'phpuser', 'php', 'news_site');

if($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}
?>
