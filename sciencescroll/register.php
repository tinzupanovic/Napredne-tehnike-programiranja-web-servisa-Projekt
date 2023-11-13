<?php
    require 'config.php';

    $registeredUser = false;
    $msg ="";

    if (isset($_POST['send'])) {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $user = $_POST['username'];
        $pass = $_POST['pass'];
        $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
        $role = "user";

        $sql = "SELECT username FROM users WHERE username LIKE ?";
        $stmt = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $user);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        }

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $msg = 'Username already exists!';
        } else {
            $sql = "INSERT INTO users (name, surname, username, password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($dbc);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 'sssss', $name, $surname, $user, $hashed_pass, $role);
                mysqli_stmt_execute($stmt);
                $registeredUser = true;

                $sql2 = "SELECT id, username, password, role FROM users WHERE username = ?";
                $stmt2 = $dbc->prepare($sql2);
                $stmt2->bind_param("s", $user);
                $stmt2->execute();
                $result2 = $stmt2->get_result();

                $userdodan = $result2->fetch_assoc();

                $_SESSION['username'] = $user;
                $_SESSION['user_id'] = $userdodan['id'];
                $_SESSION['role'] = $role;
            }
        }
        mysqli_close($dbc);
    }
?>
<script type="text/javascript">
    function redirectWithDelay(url, delay) {
        setTimeout(function () {
            window.location.href = url;
        }, delay);
    }
</script>
<div class='content'>
<h1>Register</h1>
<form id="forma" enctype="multipart/form-data" action="" method="post">
    <div class="form-item">
        <label for="name">Name: </label>
        <span id="msgName" class="msgColor"></span>
        <div class="form-field">
            <input type="text" name="name" id="name" class="form-field-textual">
        </div>
    </div>
    <div class="form-item">
        <label for="surname">Surname: </label>
        <span id="msgSurname" class="msgColor"></span>
        <div class="form-field">
            <input type="text" name="surname" id="surname" class="form-field-textual">
        </div>
    </div>
    <div class="form-item">
        <label for="username">Username:</label>
        <span id="msgUsername" class="msgColor"></span>
        <?php if($msg != "") { echo '<br><span style="color:red;">'.$msg.'</span>'; }; ?>
        <div class="form-field">
            <input type="text" name="username" id="username" class="form-field-textual">
        </div>
    </div>
    <div class="form-item">
        <label for="pass">Password: </label>
        <span id="msgPass" class="msgColor"></span>
        <div class="form-field">
            <input type="password" name="pass" id="pass" class="form-field-textual">
        </div>
    </div>
    <div class="form-item">
        <label for="passRep">Repeat password: </label>
        <span id="msgPassRep" class="msgColor"></span>
        <div class="form-field">
            <input type="password" name="passRep" id="passRep" class="form-field-textual">
        </div>
    </div>
    <?php if ($registeredUser): ?>
            <p style="color: green;">Registration successful! Redirecting...</p>
            <script type="text/javascript">
                redirectWithDelay('index.php', 1000);
            </script>
        <?php endif; ?>
    <div class="form-item">
        <button type="submit" value="Register" class="my-button" id="send" name="send">Register</button>
    </div>
</form>
    </div>
<script type="text/javascript">
    document.getElementById("send").onclick = function(event) {
        var sendForm = true;
        var fieldName = document.getElementById("name");
        var name = document.getElementById("name").value;
        if (name.length == 0 || name == "") {
            sendForm = false;
            fieldName.style.border="1px solid red";
            document.getElementById("msgName").innerHTML="<br>Enter name!<br>";
        } else {
            fieldName.style.border="1px solid green";
            document.getElementById("msgName").innerHTML="";
        }
        var fieldSurname = document.getElementById("surname");
        var surname = document.getElementById("surname").value;
        if (surname.length == 0 || surname == "") {
            sendForm = false;
            fieldSurname.style.border="1px solid red";
            document.getElementById("msgSurname").innerHTML="<br>Enter surname!<br>";
        } else {
            fieldSurname.style.border="1px solid green";
            document.getElementById("msgSurname").innerHTML="";
        }
        var fieldUsername = document.getElementById("username");
        var username = document.getElementById("username").value;
        if (username.length == 0 || username == "") {
            sendForm = false;
            fieldUsername.style.border="1px solid red";
            document.getElementById("msgUsername").innerHTML="<br>Enter username!<br>";
        } else {
            fieldUsername.style.border="1px solid green";
            document.getElementById("msgUsername").innerHTML="";
        }
        var fieldPass = document.getElementById("pass");
        var pass = document.getElementById("pass").value;
        var fieldPassRep = document.getElementById("passRep");
        var passRep = document.getElementById("passRep").value;
        if (pass.length == 0 || passRep.length == 0 || pass != passRep || pass == "" || passRep == "") {
            sendForm = false;
            fieldPass.style.border="1px solid red";
            fieldPassRep.style.border="1px solid red";
            document.getElementById("msgPass").innerHTML="<br>Password does not match!<br>";
            document.getElementById("msgPassRep").innerHTML="<br>Password does not match!<br>";
        } else {
            fieldPass.style.border="1px solid green";
            fieldPassRep.style.border="1px solid green";
            document.getElementById("msgPass").innerHTML="";
            document.getElementById("msgPassRep").innerHTML="";
        }
        if (sendForm != true) {
            event.preventDefault();
        }
    };
</script>
