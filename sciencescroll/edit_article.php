<?php
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?menu=6");
    exit();
}

if (isset($_POST['update_article'])) {
    $articleId = $_GET['idtoedit'];
    $newTitle = $_POST['title'];
    $newAuthor = $_POST['author'];
    $newDate = $_POST['date'];
    $newField = $_POST['field'];
    $newContent = $_POST['content'];
    $newArchive = isset($_POST['archive']) ? 1 : 0;

    $updateArticleQuery = "UPDATE articles SET title = ?, author = ?, date = ?, field = ?, content = ?, archive = ? WHERE id = ?";
    $stmt = $dbc->prepare($updateArticleQuery);
    $stmt->bind_param("ssssssi", $newTitle, $newAuthor, $newDate, $newField, $newContent, $newArchive, $articleId);

    if ($stmt->execute()) {
        header("Location: index.php?menu=7");
        exit();
    } else {
        echo "Article update failed. Please try again.";
    }
}

if (isset($_GET['idtoedit'])) {
    $articleId = $_GET['idtoedit'];
    $getArticleQuery = "SELECT id, title, author, field, content FROM articles WHERE id = ?";
    $stmt = $dbc->prepare($getArticleQuery);
    $stmt->bind_param("i", $articleId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $article = $result->fetch_assoc();
    } else {
        header("Location: index.php?menu=2");
        exit();
    }
}

?>
<div class='content'>
<h1>Edit Article</h1>
<form id="forma2" name="newarticle" method="post" enctype="multipart/form-data">
    <div class="form-item">
        <label for="title">Title:</label><br>
        <span id="msgTitle" class="msgColor"></span>
        <div class="form-field">
            <input type="text" name="title" class="form-field-textual" id="title" value="<?php echo $article['title']; ?>">
        </div>
    </div>
    <div class="form-item">
        <label for="author">Author:</label><br>
        <span id="msgAuthor" class="msgColor"></span>
        <div class="form-field">
            <input type="text" name="author" class="form-field-textual" id="author" value="<?php echo $article['author']; ?>">
        </div>
    </div>
    <div class="form-item">
        <label for="date">Date:</label>
        <span id="msgDate" class="msgColor"></span>
        <div class="form-field">
            <input type="date" name="date" id="date" class="form-field-textual" value="<?php echo $article['date']; ?>">
        </div>
    </div>
    <div class="form-item">
        <label for="field">Field: </label><br>
        <span id="msgField" class="msgColor"></span>
        <div class="form-field">
            <select name="field" class="form-field-textual" id="field" value="<?php echo $article['field']; ?>">
                <option value="<?php echo $article['field']; ?>" disabled selected><?php echo $article['field']; ?></option>
                <option value="Physics">Physics</option>
                <option value="Mathematics">Mathematics</option>
                <option value="Quantitative Biology">Quantitative Biology</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Quantitative Finance">Quantitative Finance</option>
                <option value="Statistics">Statistics</option>
                <option value="Electrical Engineering and Systems Science">Electrical Engineering and Systems Science</option>
                <option value="Economics">Economics</option>
            </select>
        </div>
    </div>
    <div class="form-item">
        <label for="content">Content: </label><br>
        <span id="msgContent" class="msgColor"></span>
        <div class="form-field">
            <textarea name="content" cols="30" rows="10" class="form-field-textual" id="content"><?php echo $article['content']; ?></textarea>
        </div>
    </div>
    <div class="form-item">
        <label>Save to archive:
            <div class="form-field">
                <input type="checkbox" name="archive">
            </div>
        </label>
    </div>
    <div class="form-item">
        <button name="update_article" type="submit" value="Update" class="my-button marginzatop" id="sendd">Update</button>
    </div>
</form>
</div>
<script type = "text/javascript"> 
    document.getElementById("sendd").onclick = function(event) { 
        var sendForm = true; 
        var fieldTitle = document.getElementById("title"); 
        var title = document.getElementById("title").value; 
        if (title.length < 5 || title.length > 255) { 
            sendForm = false; 
            fieldTitle.style.border="1px solid red"; 
            document.getElementById("msgTitle").innerHTML="Article title has to be between 5 and 255 characters!<br>"; 
        } else { 
            fieldTitle.style.border="1px solid green"; 
            document.getElementById("msgTitle").innerHTML=""; 
        }

        var fieldAuthor = document.getElementById("author");
        var author = document.getElementById("author").value;
        if (author.length < 3 || author.length > 100) {
            sendForm = false;
            fieldAuthor.style.border = "1px solid red";
            document.getElementById("msgAuthor").innerHTML = "Author name has to be between 3 and 100 characters!<br>";
        } else {
            fieldAuthor.style.border = "1px solid green";
            document.getElementById("msgAuthor").innerHTML = "";
        }

        var fieldDate = document.getElementById("date");
        var date = document.getElementById("date").value;
        if (date === "") {
            sendForm = false;
            fieldDate.style.border = "1px solid red";
            document.getElementById("msgDate").innerHTML = "Please select a date!<br>";
        } else {
            fieldDate.style.border = "1px solid green";
            document.getElementById("msgDate").innerHTML = "";
        }

        var fieldField = document.getElementById("field");
        var fieldSelected = fieldField.options[fieldField.selectedIndex].value;
        if (fieldSelected === "") {
            sendForm = false;
            fieldField.style.border = "1px solid red";
            document.getElementById("msgField").innerHTML = "Please select a field!<br>";
        } else {
            fieldField.style.border = "1px solid green";
            document.getElementById("msgField").innerHTML = "";
        }

        var fieldContent = document.getElementById("content");
        var content = document.getElementById("content").value;
        if (content.length == 0) {
            sendForm = false;
            fieldContent.style.border = "1px solid red";
            document.getElementById("msgContent").innerHTML = "Article content has to be entered!<br>";
        } else {
            fieldContent.style.border = "1px solid green";
            document.getElementById("msgContent").innerHTML = "";
        }

        if (sendForm != true) { 
            event.preventDefault(); 
        } 
    }; 
</script>