<?php
session_start();
require('../includes/config.php');

$user_id = $_SESSION['user_id'];

$email = trim($_POST['email']);
$oldPassword = trim($_POST['oldPass']);
$newPassword = trim($_POST['newPass']);

if(!preg_match("/^\w+@\w+\.\w+/", $email)) {
    $_SESSION['message'] = 'Email invalid format';
    header("Location: profile.php");
    exit();
}

if (strlen($newPassword) < 6) {
    $_SESSION['message'] = 'Password should be at least 6 characters';
    header("Location: profile.php");
    exit();
}

$sql = "SELECT password FROM user WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $currentPassword = $row['password'];

    if (sha1($oldPassword) === $currentPassword) {
        $newPass = sha1($newPassword);

        $sql = "UPDATE user SET email = '$email', password = '$newPass' WHERE user_id = '$user_id'";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = 'Password and email updated successfully!';
            header("Location: profile.php");
        } else {
            $_SESSION['message'] = 'Error updating password or email.';
            header("Location: profile.php");
        }
    } else {
        $_SESSION['message'] = 'Current password is incorrect.';
        header("Location: profile.php");
    }
} else {
    $_SESSION['message'] = 'User not found.';
    header("Location: profile.php");
}

?>