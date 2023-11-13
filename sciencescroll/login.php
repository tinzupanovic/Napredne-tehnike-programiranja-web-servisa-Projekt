<?php
require 'config.php';

$successfulLogin = false;
$admin = false;
$wrongID = false;

if (isset($_POST['send'])) {
    $loginUsername = $_POST['username'];
    $loginUserPass = $_POST['pass'];

    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("s", $loginUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($loginUserPass, $user['password'])) {
            $successfulLogin = true;
            
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $wrongID = false;

            header("Location: index.php");
            exit;
        } else {
            $successfulLogin = false;
            $wrongID = true;
        }
    }
}
?>
<div class='content'>
<h1>Login</h1>
<form id=forma enctype="multipart/form-data" action="" method="POST">
    <div class="form-item">
        <label for="username">Username:</label>
        <span id="msgUsername" class="msgColor"></span>
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
    <?php
    if ($wrongID) {
        echo "<p style='margin-bottom: 10px;'>Wrong Username or Password! Create an account by clicking the link below.</p>";
    }
    ?>
    <a class="gotoreg" href="index.php?menu=5">Don't have an account? Register here.</a>
    <div class="spc"></div>
    <div class="form-item">
        <button type="submit" value="Login" class="my-button marginzatop" id="send" name="send">Login</button>
    </div>
</form>
</div>
<script type="text/javascript">
    document.getElementById("send").onclick = function (event) {
        var sendForm = true;
        var fieldUsername = document.getElementById("username");
        var username = document.getElementById("username").value;
        if (username.length == 0 || username == "") {
            sendForm = false;
            fieldUsername.style.border = "1px solid red";
            document.getElementById("msgUsername").innerHTML = "<br>Enter username!<br>";
        } else {
            fieldUsername.style.border = "1px solid green";
            document.getElementById("msgUsername").innerHTML = "";
        }
        var fieldPass = document.getElementById("pass");
        var pass = document.getElementById("pass").value;
        if (pass.length == 0) {
            sendForm = false;
            fieldPass.style.border = "1px solid red";
            document.getElementById("msgPass").innerHTML = "<br>Enter password!<br>";
        } else {
            fieldPass.style.border = "1px solid green";
            document.getElementById("msgPass").innerHTML = "";
        }
        if (sendForm != true) {
            event.preventDefault();
        }
    }
</script>