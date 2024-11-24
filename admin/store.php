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
        header("Location: create.php");
        exit();
    }
    
    if (!preg_match("/^[a-zA-Z0-9\s,.-]+$/", $address)) {
        $_SESSION['message'] = 'Invalid address.';
        header("Location: create.php");
        exit();
    }

    if(!preg_match("/^\w+@\w+\.\w+/", $email)) {
        $_SESSION['message'] = 'Email invalid format';
        header("Location: create.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['message'] = 'Password should be at least 6 characters';
        header("Location: create.php");
        exit();
    }

    if ($password !== $confirmPass) {
        $_SESSION['message'] = 'Passwords do not match';
        header("Location: create.php");
        exit();
    }

    if (!preg_match("/^09\d{2}-\d{3}-\d{4}$/", $contact)) {
        $_SESSION['message'] = 'Contact number must be in the format 09XX-XXX-XXXX';
        header("Location: create.php");
        exit();
    }


    if (isset($_FILES['user_img'])) {
        $allowed_types = ["image/jpeg", "image/jpg", "image/png"];
        if (in_array($_FILES['user_img']['type'], $allowed_types)) {
            $source = $_FILES['user_img']['tmp_name'];

            $target = '../user/uploads/' . basename($_FILES['user_img']['name']);

            if (move_uploaded_file($source, $target)) {
                $_SESSION['imageError'] = null; 
            } else {
                $_SESSION['imageError'] = "Could not upload image.";
                header("Location: create.php");
                exit();
            }
        } else {
            $_SESSION['imageError'] = "Wrong file type.";
            header("Location: create.php");
            exit();
        }
    }


    $password = sha1($password);

    $sql1 = "INSERT INTO user (fname, lname, address, contact_number, email, password, user_img, role_id, status_id) 
        VALUES ('$fname', '$lname', '$address', '$contact', '$email', '$password', '$target', '1', '1')";
    $result1 = mysqli_query($conn, $sql1);

    if ($result1) {
        $_SESSION['success'] = 'profile saved';
        header("Location: users.php");
    }

}
?>