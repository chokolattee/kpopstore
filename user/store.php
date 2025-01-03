<?php
session_start();
include("../includes/config.php");
include("../includes/header.php");

if (isset($_POST['submit'])) {
    $fname = ucwords(trim($_POST['fname']));
    $lname = ucwords(trim($_POST['lname']));
    $address = ucwords(trim($_POST['address']));
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPass = trim($_POST['confirmPass']);

    if (!preg_match("/^[a-zA-Z\s-]+$/", $fname) || !preg_match("/^[a-zA-Z\s-]+$/", $lname)) {
        $_SESSION['message'] = 'Invalid name.';
        header("Location: register.php");
        exit();
    }
    
    if (!preg_match("/^[a-zA-Z0-9\s,.-]+$/", $address)) {
        $_SESSION['message'] = 'Invalid address.';
        header("Location: register.php");
        exit();
    }

    if(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        $_SESSION['message'] = 'Email invalid format';
        header("Location: register.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['message'] = 'Password should be at least 6 characters';
        header("Location: register.php");
        exit();
    }

    if ($password !== $confirmPass) {
        $_SESSION['message'] = 'Passwords do not match';
        header("Location: register.php");
        exit();
    }

    if (!preg_match("/^09\d{2}-\d{3}-\d{4}$/", $contact)) {
        $_SESSION['message'] = 'Contact number must be in the format 09XX-XXX-YYYY';
        header("Location: register.php");
        exit();
    }

    $checkEmailQuery = "SELECT email FROM user WHERE email = '$email' LIMIT 1";
    $checkEmailResult = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        $_SESSION['message'] = 'Email is already in use';
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
            header("Location: register.php");
        }
    }

    $password = sha1($password);

    $sql1 = "INSERT INTO user (fname, lname, address, contact_number, email, password, user_img, role_id, status_id) 
        VALUES ('$fname', '$lname', '$address', '$contact', '$email', '$password', '$target', '2', '1')";
    $result1 = mysqli_query($conn, $sql1);

    if ($result1) {
        $_SESSION['success'] = 'profile saved';
        header("Location: login.php");
    }

}
?>