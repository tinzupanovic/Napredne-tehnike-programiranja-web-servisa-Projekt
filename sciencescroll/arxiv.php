<h1 class="content">arXiv Search</h1>
<form id="filter-form" method="get" action="http://localhost/sciencescroll/index.php">
    <input type="hidden" name="menu" value="10">
    <input type="text" name="query" placeholder="Search arXiv" />
    <input type="submit" value="Search" />
</form>

<?php
if (isset($_GET['query'])) {
    $query = urlencode($_GET['query']);

    $api_url = "http://export.arxiv.org/api/query?search_query=all:" . $query . "&start=0&max_results=1";

    $response = file_get_contents($api_url);

    $xml = simplexml_load_string($response);

    echo "<h2 class='content'>Search Results for '$query':</h2>";
    $entry = $xml->entry;
    $arxivId = (string)$entry->id;
    $title = (string)$entry->title;
    $authors = [];
    foreach ($entry->author as $author) {
        $authors[] = (string)$author->name;
    }
    $publishedDate = (string)$entry->published;
    $summary = (string)$entry->summary;
    $sourceLink = (string)$entry->link[0]['href'];
    echo "<div class='content'>";
    echo "<h1>Title: $title</h1><br>";
    echo "<p><strong>Authors:</strong> " . implode(', ', $authors) . "</p><br>";
    echo "<p><strong>Date:</strong> $publishedDate</p><br>";
    echo "<p><strong>Content:</strong> $summary</p><br>";
    echo "Source Link: <a id='links' href='$sourceLink' target='_blank'>Read on arXiv</a><br>";
    echo "</div>";

    $getCommentsQuery = "SELECT commentsarxiv.id, users.username, commentsarxiv.comment, commentsarxiv.timestamp
                    FROM commentsarxiv
                    JOIN users ON commentsarxiv.user_id = users.id
                    WHERE commentsarxiv.article_id = ?";
    $stmt = $dbc->prepare($getCommentsQuery);
    $stmt->bind_param("s", $arxivId);
    $stmt->execute();
    $commentsResult = $stmt->get_result();
    echo '<div id="comments-section">';
    echo '<h2">Comments</h2>';
    if (isset($commentsResult) && $commentsResult->num_rows > 0) {
        while ($comment = $commentsResult->fetch_assoc()) {
            $commentID = $comment['id'];
            $commentTimestamp = $comment['timestamp'];
            $commentUsername = $comment['username'];
            $commentText = $comment['comment'];
            echo "<p><strong>{$commentUsername}</strong> - <em>{$commentTimestamp}</em><br>{$commentText}</p>";
            if (isset($_SESSION['username']) && $_SESSION['username'] == $commentUsername) {
                echo "<form method='post'>
                    <input type='hidden' name='delete_comment_id' value='{$commentID}'>
                    <input type='submit' class='my-button' value='Delete' id='delete' name='delete'>
                    </form>";
            }
        }
    } else {
        echo "</br>No comments yet.";
    }
    echo '</div>';
    echo '<div class="comment">';
    if (isset($_SESSION['username'])) {
        echo '<form enctype="multipart/form-data" method="post">';
        echo "<textarea name='comment' placeholder='Add a comment' rows='4' required></textarea><br>
        <input type='hidden' name='comment_id' value='{$arxivId}'>
        <div class='form-item'>
            <button type='submit' value='Add comment' class='my-button' id='send' name='send'>Add comment</button>
        </div>" ;
        }
    echo '</form>';
    echo '</div>';

}

if (isset($_POST['delete']) && isset($_POST['delete_comment_id'])) {
    $deleteCommentId = $_POST['delete_comment_id'];
    $deleteCommentQuery = "DELETE FROM commentsarxiv WHERE id = ?";
    $stmt = $dbc->prepare($deleteCommentQuery);
    $stmt->bind_param("i", $deleteCommentId);

    if ($stmt->execute()) {
        echo "Comment deleted successfully.";
        header("Location: index.php?menu=10&query=" . urlencode($_GET['query']));
    } else {
        echo "Comment deletion failed. Please try again.";
    }
}

if (isset($_POST['send']) && isset($_SESSION['username'])) {
    $comment = $_POST['comment'];
    $arxivId = $_POST['comment_id'];
    
    $insertCommentQuery = "INSERT INTO commentsarxiv (article_id, user_id, comment, timestamp) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt = $dbc->prepare($insertCommentQuery);
    $stmt->bind_param("sis", $arxivId, $_SESSION['user_id'], $comment);
    if ($stmt->execute()) {
        echo "Comment added successfully.";
        header("Location: index.php?menu=10&query=" . urlencode($_GET['query']));
    } else {
        echo "Comment adding failed. Please try again.";
    }
}


?>

