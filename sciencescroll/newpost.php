<?php
    require 'config.php';

    if (!isset($_SESSION['username'])) {
        header("Location: index.php?menu=6.php");
        exit();
    }

    if (isset($_POST['send'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $date = $_POST['date'];
        $field = $_POST['field'];
        $content = $_POST['content'];
        $archive = isset($_POST['archive']) ? 1 : 0;
        if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == UPLOAD_ERR_OK) {
            $pdfName = $_FILES['pdf']['name'];
            $pdfTmpName = $_FILES['pdf']['tmp_name'];
            $pdfType = $_FILES['pdf']['type'];
            $pdfSize = $_FILES['pdf']['size'];
    
            $uploadDirectory = 'uploads/';
    
            $pdfFileName = $uploadDirectory . uniqid() . '_' . $pdfName;
    
            if (move_uploaded_file($pdfTmpName, $pdfFileName)) {
    
                $insertArticleQuery = "INSERT INTO articles (title, author, date, field, content, pdf_filename, archive) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $dbc->prepare($insertArticleQuery);
                $stmt->bind_param("ssssssi", $title, $author, $date, $field, $content, $pdfFileName, $archive);
    
                if ($stmt->execute()) {
                    echo "<div class='content'>Article posted successfully. <a class='my-button' href='index.php?menu=2'>View articles</a></div>";
                } else {
                    echo "Article posting failed: <p> koja mgadsgasfadsifgsa </p>" . $stmt->error;
                }
            } else {
                echo "Error uploading the PDF file.";
            }
        } else {
            echo "No PDF file uploaded or an error occurred during upload.";
        }
    }
?>
<div class='content'>
<h1>New Article</h1>
<form id="forma2" name="newarticle" method="post" enctype="multipart/form-data">
    <div class="form-item">
        <label for="title">Title:</label><br>
        <span id="msgTitle" class="msgColor"></span>
        <div class="form-field">
            <input type="text" name="title" class="form-field-textual" id="title">
        </div>
    </div>
    <div class="form-item">
        <label for="author">Author:</label><br>
        <span id="msgAuthor" class="msgColor"></span>
        <div class="form-field">
            <input type="text" name="author" class="form-field-textual" id="author">
        </div>
    </div>
    <div class="form-item">
        <label for="date">Date:</label>
        <span id="msgDate" class="msgColor"></span>
        <div class="form-field">
            <input type="date" name="date" id="date" class="form-field-textual">
        </div>
    </div>
    <div class="form-item">
        <label for="field">Field: </label><br>
        <span id="msgField" class="msgColor"></span>
        <div class="form-field">
            <select name="field" class="form-field-textual" id="field">
                <option value="" disabled selected>Select Field</option>
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
            <textarea name="content" cols="30" rows="10" class="form-field-textual" id="content"></textarea>
        </div>
    </div>
    <div class="form-item">
        <label for="pdf">PDF File: </label>
        <span id="msgPdf" class="msgColor"></span>
        <div class="form-field">
            <input type="file" name="pdf" id="pdf" accept=".pdf">
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
        <button type="reset" value="Decline" class="my-button">Decline</button>
        <button name="send" type="submit" value="Accept" class="my-button" id="sendd">Accept</button>
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

        var fieldPdf = document.getElementById("pdf");
        var pdf = document.getElementById("pdf").value;
        if (pdf === "") {
            sendForm = false;
            fieldPdf.style.border = "1px solid red";
            document.getElementById("msgPdf").innerHTML = "Please select a PDF file!<br>";
        } else {
            var allowedExtensions = /(\.pdf)$/i;
            if (!allowedExtensions.exec(pdf)) {
                sendForm = false;
                fieldPdf.style.border = "1px solid red";
                document.getElementById("msgPdf").innerHTML = "Please select a valid PDF file!<br>";
            } else {
                fieldPdf.style.border = "1px solid green";
                document.getElementById("msgPdf").innerHTML = "";
            }
        }

        if (sendForm != true) { 
            event.preventDefault(); 
        } 
    }; 
</script>
