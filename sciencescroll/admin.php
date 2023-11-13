<?php
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?menu=6");
    exit();
}

if (isset($_GET['delete_article'])) {
    $articleId = $_GET['delete_article'];

    $fetchCommentsQuery = "SELECT id FROM comments WHERE article_id = ?";
    $stmt = $dbc->prepare($fetchCommentsQuery);
    $stmt->bind_param("i", $articleId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $commentId = $row['id'];
    
            $deleteCommentQuery = "DELETE FROM comments WHERE id = ?";
            $deleteStmt = $dbc->prepare($deleteCommentQuery);
            $deleteStmt->bind_param("i", $commentId);
            $deleteStmt->execute();
            $deleteStmt->close();
        }
    }


    $deleteArticleQuery = "DELETE FROM articles WHERE id = ?";
    $stmt = $dbc->prepare($deleteArticleQuery);
    $stmt->bind_param("i", $articleId);

    if ($stmt->execute()) {
        header("Location: index.php?menu=7");
        exit();
    } else {
        echo "Article deletion failed. Please try again.";
    }
}

if (isset($_GET['delete_user'])) {
    $userId = $_GET['delete_user'];

    $fetchCommentsQuery = "SELECT id FROM comments WHERE user_id = ?";
    $stmt = $dbc->prepare($fetchCommentsQuery);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $commentId = $row['id'];
    
            $deleteCommentQuery = "DELETE FROM comments WHERE id = ?";
            $deleteStmt = $dbc->prepare($deleteCommentQuery);
            $deleteStmt->bind_param("i", $commentId);
            $deleteStmt->execute();
            $deleteStmt->close();
        }
    }

    $fetchCommentsQuery = "SELECT id FROM commentsarxiv WHERE user_id = ?";
    $stmt = $dbc->prepare($fetchCommentsQuery);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $commentId = $row['id'];
    
            $deleteCommentQuery = "DELETE FROM commentsarxiv WHERE id = ?";
            $deleteStmt = $dbc->prepare($deleteCommentQuery);
            $deleteStmt->bind_param("i", $commentId);
            $deleteStmt->execute();
            $deleteStmt->close();
        }
    }

    $deleteUserQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $dbc->prepare($deleteUserQuery);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        header("Location: index.php?menu=7");
        exit();
    } else {
        echo "User deletion failed. Please try again.";
    }
}

$getArticlesQuery = "SELECT id, title, author, date, field, content, pdf_filename FROM articles";
$result = $dbc->query($getArticlesQuery);

$getUsersQuery = "SELECT id, username, surname, username, role FROM users";
$usersResult = $dbc->query($getUsersQuery);
?>
<div class='content'>
<h1>Admin Dashboard</h1>
<form class="marginzabot" method="post">
        <input type="submit" class="my-button" name="view_articles" value="View Articles">
        <input type="submit" class="my-button" name="view_users" value="View Users">
    </form>

    <?php
    if (isset($_POST['view_users'])) {
        echo "<h2>Users</h2>";
        echo "<ul>";
            if ($usersResult->num_rows > 0) {
                while ($user = $usersResult->fetch_assoc()) {
                    $userId = $user['id'];
                    $username = $user['username'];
                    echo "<li>$username - <a id='links' href='index.php?menu=7&delete_user=$userId'>Delete</a> - <a id='links' href='index.php?menu=11&useridtoedit=$userId'>Edit</a></li>";
                }
            } else {
                echo "<li>No users found.</li>";
            }
        echo "</ul>";
    } else  {
        echo "<h2>Articles</h2>"; 
        echo "<ul>";
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $articleId = $row['id'];
                    $title = $row['title'];
                    echo "<li>$title - <a id='links' href='index.php?menu=7&delete_article=$articleId'>Delete</a> - <a id='links' href='index.php?menu=9&idtoedit=$articleId'>Edit</a></li>";
                }
            } else {
                echo "<li>No articles found.</li>";
            }
        echo "</ul>";
    }
    ?>
</div>
