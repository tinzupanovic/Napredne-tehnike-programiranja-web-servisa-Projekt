<?php
require 'config.php';


if (isset($_GET['id'])) {
    $articleId = $_GET['id'];

    $getArticleQuery = "SELECT title, author, date, field, content, pdf_filename FROM articles WHERE id = ?";
    $stmt = $dbc->prepare($getArticleQuery);
    $stmt->bind_param("i", $articleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $article = $result->fetch_assoc();
    } else {
        header("Location: index.php?menu=6");
        exit();
    }
    if (isset($_POST['delete']) && isset($_POST['delete_comment_id'])) {
        $deleteCommentId = $_POST['delete_comment_id'];
        $deleteCommentQuery = "DELETE FROM comments WHERE id = ?";
        $stmt = $dbc->prepare($deleteCommentQuery);
        $stmt->bind_param("i", $deleteCommentId);

        if ($stmt->execute()) {
            echo "";
        } else {
            echo "Comment deletion failed. Please try again.";
        }
    }

    if (isset($_POST['send']) && isset($_SESSION['username'])) {
        $comment = $_POST['comment'];

        $insertCommentQuery = "INSERT INTO comments (article_id, user_id, comment, timestamp) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = $dbc->prepare($insertCommentQuery);
        $stmt->bind_param("iis", $articleId, $_SESSION['user_id'], $comment);

        if ($stmt->execute()) {
            echo "";
        } else {
            echo "Comment adding failed. Please try again.";
        }
    }

    $getCommentsQuery = "SELECT comments.id, users.username, comments.comment, comments.timestamp
                        FROM comments
                        JOIN users ON comments.user_id = users.id
                        WHERE comments.article_id = ?";
    $stmt = $dbc->prepare($getCommentsQuery);
    $stmt->bind_param("i", $articleId);
    $stmt->execute();
    $commentsResult = $stmt->get_result();
} else {
    header("Location: articles.php");
    exit();
}
?>

<div class='content'>
<h1><?php echo $article['title']; ?></h1>
<p class='marginzabot'><strong>Author:</strong> <?php echo $article['author']; ?></p>
<p class='marginzabot'><strong>Date:</strong> <?php echo $article['date']; ?></p>
<p class='marginzabot'><strong>Field:</strong> <?php echo $article['field']; ?></p>
<p class='marginzabot'><strong>Content:</strong><br><?php echo $article['content']; ?></p>
<?php if (!empty($article['pdf_filename'])): ?>
    <p class='marginzabot'><strong>PDF:</strong> <a id="links" href="uploads/<?php echo $article['pdf_filename']; ?>" download>Download PDF</a></p>
<?php endif; ?>
</div>
<div id="comments-section">
<h2>Comments</h2>
<?php
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
                <input type='submit' class='my-button marginzatop' value='Delete' id='delete' name='delete'>
                </form>";
        }
    }
} else {
    echo "No comments yet.";
}
?>
</div>
<div class="comment">
<form enctype="multipart/form-data" method="post">
    <?php if (isset($_SESSION['username'])) {
        echo '<textarea name="comment" id="comment" placeholder="Add a comment" rows="4" required></textarea><br>
        <div class="form-item">
            <button type="submit" value="Add comment" class="my-button" id="send" name="send">Add comment</button>
        </div>' ;
        }
    ?>
</form>
</div>
<div class='content'>
<?php
echo "<h2>Suggested Articles</h2>";
echo "<div id='articles-container'>";

$suggestedArticlesQuery = "SELECT id, title, author, date, field FROM articles WHERE field = ? AND id != ? LIMIT 4";
$stmt = $dbc->prepare($suggestedArticlesQuery);
$stmt->bind_param("si", $article['field'], $articleId);
$stmt->execute();
$suggestedArticlesResult = $stmt->get_result();

if ($suggestedArticlesResult->num_rows > 0) {
    while ($suggestedArticle = $suggestedArticlesResult->fetch_assoc()) {
        $suggestedArticleId = $suggestedArticle['id'];
        $suggestedArticleTitle = $suggestedArticle['title'];
        $suggestedArticleAuthor = $suggestedArticle['author'];
        $suggestedArticleDate = $suggestedArticle['date'];
        $suggestedArticleField = $suggestedArticle['field'];
        $fieldClass = '';
            if ($suggestedArticleField === "Physics") {
                $fieldClass = 'physics';
            } elseif ($suggestedArticleField === "Mathematics") {
                $fieldClass = 'mathematics';
            } elseif ($suggestedArticleField === "Quantitative Biology") {
                $fieldClass = 'biology';
            } elseif ($suggestedArticleField === "Computer Science") {
                $fieldClass = 'cs';
            } elseif ($suggestedArticleField === "Quantitative Finance") {
                $fieldClass = 'finance';
            } elseif ($suggestedArticleField === "Statistics") {
                $fieldClass = 'statistics';
            } elseif ($suggestedArticleField === "Electrical Engineering and Systems Science") {
                $fieldClass = 'electronic';
            } elseif ($suggestedArticleField === "Economics") {
                $fieldClass = 'economics';
            }
        echo "<div class='pageart'>";
        echo "<div class='pagearticles article $fieldClass'>";
        echo "<h2><a href='index.php?id=$suggestedArticleId'>$suggestedArticleTitle</a></h2>";
        echo "<p>Author: $suggestedArticleAuthor</p>";
        echo "<p>Date: $suggestedArticleDate</p>";
        echo "<p>Field: $suggestedArticleField</p>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "No suggested articles available in this category.";
}

echo "</div>";
?>
</div>