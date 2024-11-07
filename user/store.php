<?php
session_start();
include("../includes/config.php");
include("../includes/header.php");

if (isset($_POST['submit'])) {
    // Get and sanitize form data
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPass = trim($_POST['confirmPass']);
    $role = trim($_POST['role']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'Invalid email format';
        header("Location: register.php");
        exit();
    }

    // Validate password length
    if (strlen($password) < 6) {
        $_SESSION['message'] = 'Password should be at least 6 characters';
        header("Location: register.php");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmPass) {
        $_SESSION['message'] = 'Passwords do not match';
        header("Location: register.php");
        exit();
    }

    if (isset($_FILES['user_img'])) {
        if ($_FILES['user_img']['type'] == "image/jpeg" || $_FILES['img_path']['type'] == "image/jpg" || $_FILES['img_path']['type'] == "image/png") {
            $source = $_FILES['user_img']['tmp_name'];
            $target = '../user/uploads/' . basename($_FILES['user_img']['name']);
            move_uploaded_file($source, $target) or die("Couldn't copy");
        } else {
            $_SESSION['imageError'] = "wrong file type";
            header("Location: login.php");
        }
    }

    $password = sha1($password);
    $sql = "INSERT INTO user (fname, lname, address, contact_number, email, password, role, user_img) 
        VALUES ('$fname', '$lname', '$address', '$contact', '$email', '$password', '$role', '$target')";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        $_SESSION['success'] = 'profile saved';
        header("Location: login.php");
    }

}
?>
