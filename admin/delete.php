<?php
session_start();
require('../includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please log in to access resources';
    header("Location: /kpopstore/user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id' AND r.role_id = 1 LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['message'] = 'You must be logged in as admin to access this page.';
    header("Location: /kpopstore/user/login.php");
    exit;
}

if (isset($_POST['delete']) && isset($_POST['target_user_id'])) {
    $target_user_id = $_POST['target_user_id'];

    if ($target_user_id == $user_id) {
        $_SESSION['message'] = 'You cannot delete your own account.';
        header("Location: users.php");
        exit;
    }

    $delete_user_sql = "DELETE FROM user WHERE user_id = '$target_user_id'";
    if (mysqli_query($conn, $delete_user_sql)) {
        $_SESSION['success'] = 'User deleted successfully, along with their orders.';
    } else {
        $_SESSION['message'] = 'Error deleting user: ' . mysqli_error($conn);
    }

    header("Location: users.php");
    exit;
} else {
    $_SESSION['message'] = 'Invalid request or missing parameters.';
    header("Location: users.php");
    exit;
}
?>