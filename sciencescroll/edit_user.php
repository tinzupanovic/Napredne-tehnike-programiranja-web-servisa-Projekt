<?php
require 'config.php';

$msg ="";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?menu=6");
    exit();
}

if (isset($_POST['update_user'])) {
    $userId = $_GET['useridtoedit'];
    $newUsername = $_POST['username'];
    $newSurname = $_POST['surname'];
    $newName = $_POST['name'];
    $newRole = $_POST['role'];

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
        $updateUserQuery = "UPDATE users SET username = ?, surname = ?, name = ?, role = ? WHERE id = ?";
        $stmt = $dbc->prepare($updateUserQuery);
        $stmt->bind_param("ssssi", $newUsername, $newSurname, $newName, $newRole, $userId);

        if ($stmt->execute()) {
            header("Location: index.php?menu=7");
            exit();
        } else {
            echo "User update failed. Please try again.";
        }
    }
}

if (isset($_GET['useridtoedit'])) {
    $userId = $_GET['useridtoedit'];
    $getUserQuery = "SELECT id, username, surname, name, role FROM users WHERE id = ?";
    $stmt = $dbc->prepare($getUserQuery);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        header("Location: index.php?menu=7");
        exit();
    }
}
?>
<div class='content'>
<h1>Edit User</h1>
<form id="forma2" enctype="multipart/form-data" action="" method="post">
    <div class="form-item">
        <label for="name">Name: </label>
        <span id="msgName" class="msgColor"></span>
        <div class="form-field">
            <input type="text" name="name" id="name" class="form-field-textual" value="<?php echo $user['name']; ?>">
        </div>
    </div>
    <div class="form-item">
        <label for="surname">Surname: </label>
        <span id="msgSurname" class="msgColor"></span>
        <div class="form-field">
            <input type="text" name="surname" id="surname" class="form-field-textual" value="<?php echo $user['surname']; ?>">
        </div>
    </div>
    <div class="form-item">
        <label for="username">Username:</label>
        <span id="msgUsername" class="msgColor"></span>
        <?php if($msg != "") { echo '<br><span style="color:red;">'.$msg.'</span>'; }; ?>
        <div class="form-field">
            <input type="text" name="username" id="username" class="form-field-textual" value="<?php echo $user['username']; ?>">
        </div>
    </div>
    <div class="form-item">
        <label for="role">Role: </label>
        <select name="role" id="role">
            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
        </select>
    </div>
    <div class="form-item">
        <button type="submit" value="Update" class="my-button marginzatop" id="send" name="update_user">Update</button>
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
        if (sendForm != true) {
            event.preventDefault();
        }
    };
</script>
