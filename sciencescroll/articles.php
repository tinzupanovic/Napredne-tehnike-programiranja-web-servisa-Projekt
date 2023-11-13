<form id="filter-form" method="post">
    <input type="text" name="search" placeholder="Search articles">
    <select name="category">
        <option value="">All Categories</option>
        <option value="Physics">Physics</option>
        <option value="Mathematics">Mathematics</option>
        <option value="Quantitative Biology">Quantitative Biology</option>
        <option value="Computer Science">Computer Science</option>
        <option value="Quantitative Finance">Quantitative Finance</option>
        <option value="Statistics">Statistics</option>
        <option value="Electrical Engineering and Systems Science">Electrical Engineering and Systems Science</option>
        <option value="Economics">Economics</option>
    </select>
    <input name="filter" type="submit" value="Search">
</form>
<?php
require 'config.php';

$articlesPerPage = 20;



$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

if (isset($_POST['filter'])) {
    $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
    $selectedCategory = isset($_POST['category']) ? $_POST['category'] : '';
    $page = 1;
    $offset = ($page - 1) * $articlesPerPage;
    $sql2 = "SELECT id, title, author, date, field, archive FROM articles WHERE archive = 0";

    $sql = "SELECT id, title, author, date, field, archive FROM articles WHERE archive = 0";
    if (!empty($selectedCategory)) {
        $sql .= " AND field LIKE ?";
        $sql2 .= " AND field LIKE ?";
    }
    if (!empty($searchTerm)) {
        $searchTerm = "%" . $searchTerm . "%";
        $sql .= " AND title LIKE ?";
        $sql2 .= " AND field LIKE ?";
    }
    
    $stmt2 = $dbc->prepare($sql2);

    $sql .= " LIMIT ?, ?";

    $stmt = $dbc->prepare($sql);
    if (!empty($selectedCategory) && !empty($searchTerm)) {
        $stmt->bind_param("ssii", $searchTerm, $selectedCategory, $offset, $articlesPerPage);
        $stmt2->bind_param("ss", $searchTerm, $selectedCategory);
    } elseif (!empty($selectedCategory)) {
        $stmt->bind_param("sii", $selectedCategory, $offset, $articlesPerPage);
        $stmt2->bind_param("s", $selectedCategory);
    } elseif (!empty($searchTerm)) {
        $stmt->bind_param("sii", $searchTerm, $offset, $articlesPerPage);
        $stmt2->bind_param("s", $searchTerm);
    } else {
        $stmt->bind_param("ii", $offset, $articlesPerPage);
    }

    
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='content'>";
        echo "<h1>Articles</h1>";

        if (isset($_SESSION['username'])) {
            echo '<a id="new-article-link" href="index.php?menu=8">New Article</a>';
        }
        echo "<div id='articles-container'>";

        while ($row = $result->fetch_assoc()) {
            $articleId = $row['id'];
            $title = $row['title'];
            $author = $row['author'];
            $date = $row['date'];
            $field = $row['field'];
            $fieldClass = '';
            if ($field === "Physics") {
                $fieldClass = 'physics';
            } elseif ($field === "Mathematics") {
                $fieldClass = 'mathematics';
            } elseif ($field === "Quantitative Biology") {
                $fieldClass = 'biology';
            } elseif ($field === "Computer Science") {
                $fieldClass = 'cs';
            } elseif ($field === "Quantitative Finance") {
                $fieldClass = 'finance';
            } elseif ($field === "Statistics") {
                $fieldClass = 'statistics';
            } elseif ($field === "Electrical Engineering and Systems Science") {
                $fieldClass = 'electronic';
            } elseif ($field === "Economics") {
                $fieldClass = 'economics';
            }
            echo "<div class='pageart'>";
            echo "<div class='pagearticles article $fieldClass'>";
            echo "<h2><a href='index.php?id=$articleId'>$title</a></h2>";
            echo "<p>Author: $author</p>";
            echo "<p>Date: $date</p>";
            echo "<p>Field: $field</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";

        $totalArticles = $result2->num_rows;
        $totalPages = ceil($totalArticles / $articlesPerPage);

        echo "<div class='pagination'>";
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a class='page-number' href='index.php?menu=2&page=$i'>$i</a> ";
        }
        echo "</div>";
        echo "</div>";
    } else {
        echo "No articles found.";
    }
} else {
    $sql2 = "SELECT id, title, author, date, field, archive FROM articles WHERE archive = 0";
    $stmt2 = $dbc->prepare($sql2);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    $getArticlesQuery = "SELECT id, title, author, date, field, archive FROM articles WHERE archive = 0";

    $offset = ($page - 1) * $articlesPerPage;
    $getArticlesQuery .= " LIMIT ?, ?";

    $stmt = $dbc->prepare($getArticlesQuery);
    $stmt->bind_param("ii", $offset, $articlesPerPage);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='content'>";
        echo "<h1>Articles</h1>";

        if (isset($_SESSION['username'])) {
            echo '<a id="new-article-link" href="index.php?menu=8">New Article</a>';
        }
        echo "<div id='articles-container'>";

        while ($row = $result->fetch_assoc()) {
            $articleId = $row['id'];
            $title = $row['title'];
            $author = $row['author'];
            $date = $row['date'];
            $field = $row['field'];
            $fieldClass = '';
            if ($field === "Physics") {
                $fieldClass = 'physics';
            } elseif ($field === "Mathematics") {
                $fieldClass = 'mathematics';
            } elseif ($field === "Quantitative Biology") {
                $fieldClass = 'biology';
            } elseif ($field === "Computer Science") {
                $fieldClass = 'cs';
            } elseif ($field === "Quantitative Finance") {
                $fieldClass = 'finance';
            } elseif ($field === "Statistics") {
                $fieldClass = 'statistics';
            } elseif ($field === "Electrical Engineering and Systems Science") {
                $fieldClass = 'electronic';
            } elseif ($field === "Economics") {
                $fieldClass = 'economics';
            }
            echo "<div class='pageart'>";
            echo "<div class='pagearticles article $fieldClass'>";
            echo "<h2><a href='index.php?id=$articleId'>$title</a></h2>";
            echo "<p>Author: $author</p>";
            echo "<p>Date: $date</p>";
            echo "<p>Field: $field</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";

        $totalArticles = $result2->num_rows;
        $totalPages = ceil($totalArticles / $articlesPerPage);

        echo "<div class='pagination'>";
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a class='page-number' href='index.php?menu=2&page=$i'>$i</a> ";
        }
        echo "</div>";
        echo "</div>";
    } else {
        echo "No articles found.";
    }
}
?>
