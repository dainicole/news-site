<?php
require 'database.php';
//query to collect all stories
$storiesQuery = $mysqli->prepare("
    SELECT stories.id, stories.title, stories.username, stories.link, stories.content
    FROM stories
    JOIN users ON stories.username = users.username
    ORDER BY stories.id DESC
");

if (!$storiesQuery) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$storiesQuery->execute();
$storiesQuery->bind_result($id, $title, $username, $link, $content);

$allStories = [];
while ($storiesQuery->fetch()) {
    $allStories[] = [
        'id' => $id,
        'title' => $title,
        'username' => $username,
        'link' => $link,
        'content' => $content,
    ];
}

$storiesQuery->close();
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
        //display stories collected from query
        foreach ($allStories as $story) {
            echo "<h2>".htmlentities($story['title']) . "</h2>";
            echo "<p>".htmlentities($story['content'])."</p>";
            if (!empty($story['link'])) {
                echo "<p>Link: <a href='" . htmlentities($story['link']) . "'>" . htmlentities($story['link']) . "</a></p>";
            }
            echo "<p>Posted by: ".htmlentities($story['username']) . "</p>";
        }
    ?>
</body>
</html>
