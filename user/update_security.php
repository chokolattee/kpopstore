<?php
session_start();
require('../includes/config.php');

$user_id = $_SESSION['user_id'];

$email = trim($_POST['email']);
$oldPassword = trim($_POST['oldPass']);
$newPassword = trim($_POST['newPass']);

if (!preg_match("/^\w+@\w+\.\w+/", $email)) {
    $_SESSION['message'] = 'Email invalid format';
    header("Location: register.php");
    exit();
}

if (strlen($newPassword) < 6 && !empty($newPassword)) {
    $_SESSION['message'] = 'Password should be at least 6 characters';
    header("Location: profile.php");
    exit();
}

$emailCheckQuery = "SELECT user_id FROM user WHERE email = '$email' AND user_id != '$user_id'";
$emailCheckResult = mysqli_query($conn, $emailCheckQuery);

if ($emailCheckResult && mysqli_num_rows($emailCheckResult) > 0) {
    $_SESSION['message'] = 'This email is already registered with another account.';
    header("Location: profile.php");
    exit();
}

$sql = "SELECT password, email FROM user WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $currentPassword = $row['password'];
    $currentEmail = $row['email'];

    if (sha1($oldPassword) === $currentPassword || empty($oldPassword)) {
        $updateQuery = "UPDATE user SET email = '$email' WHERE user_id = '$user_id'";  // Default query updates email
        $message = 'Email updated successfully!';  // Default message for email only

        if (!empty($newPassword)) {
            $newPass = sha1($newPassword);
            $updateQuery = "UPDATE user SET email = '$email', password = '$newPass' WHERE user_id = '$user_id'";
            $message = 'Email and password updated successfully!';
        } elseif (empty($newPassword) && $email !== $currentEmail) {
            // If only email is updated, the message will be for email update
            $message = 'Email updated successfully!';
        }

        // Check if only the password has been updated (email remains the same)
        if (!empty($newPassword) && $email === $currentEmail) {
            $updateQuery = "UPDATE user SET password = '$newPass' WHERE user_id = '$user_id'";
            $message = 'Password updated successfully!';
        }

        if (mysqli_query($conn, $updateQuery)) {
            $_SESSION['message'] = $message;
            header("Location: profile.php");
        } else {
            $_SESSION['message'] = 'Error updating email or password.';
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

