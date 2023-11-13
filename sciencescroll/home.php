<?php
require 'config.php';
?>
<div id="main">
<div id="menu">
    <div class="scsc"> 
    </div>
    <section>
        <h2>About Us</h2>
        <p>Welcome to ScienceScroll, your go-to platform for exploring, sharing, and discussing the latest scientific research and discoveries. We're passionate about making scientific knowledge easily accessible to everyone.</p>
        <p>Our mission is to create a community of curious minds, from researchers and scientists to enthusiasts and students, all coming together to stay updated on groundbreaking research and contribute to the world of science.</p>
    </section>
    <section>
        <h2>Key Features</h2>
        <ul>
            <li>Access a vast library of scientific papers and articles from various fields.</li>
            <li>Search, browse, and filter articles based on your interests and preferences.</li>
            <li>Engage in meaningful discussions and provide valuable insights through comments and discussions on articles.</li>
            <li>Stay connected with the latest research from ArXiv via our seamless integration with their API.</li>
        </ul>
    </section>
    <section>
        <a class="gotoreg" href="index.php?menu=5"><h2>Join Our Community</h2></a>
        <p>ScienceScroll is more than just a website; it's a community of individuals who are passionate about science. Join us today, create an account, and start exploring the limitless world of scientific knowledge. Whether you're a seasoned researcher or a budding scientist, we welcome you to be a part of our journey.</p>
    </section>
</div>
<section id="latest-articles">
    <h2>Latest Articles</h2>
    <?php
    $query = "SELECT id, title, author, date, field FROM articles ORDER BY date DESC LIMIT 8";
    $result = $dbc->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
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

            echo "<div class='article $fieldClass'>";
            echo "<h2><a href='index.php?id=$id'>$title</a></h2>";
            echo "<p>Author: $author</p>";
            echo "<p>Date: $date</p>";
            echo "<p>Field: $field</p>";
            echo "</div>";
        }
    } else {
        echo 'No articles found.';
    }
    ?>
</section>
</div>