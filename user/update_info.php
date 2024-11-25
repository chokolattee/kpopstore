<?php
session_start();
require('../includes/config.php');

$user_id = $_SESSION['user_id'];

$fname = ucwords(trim($_POST['fname']));
    $lname = ucwords(trim($_POST['lname']));
    $address = ucwords(trim($_POST['address']));
$contact = trim($_POST['contact']);
$userimg = $_SESSION['user_img'];

if (!preg_match("/^[a-zA-Z\s-]+$/", $fname) || !preg_match("/^[a-zA-Z\s-]+$/", $lname)) {
    $_SESSION['message'] = 'Invalid name.';
    header("Location: profile.php");
    exit();
}

if (!preg_match("/^[a-zA-Z0-9\s,.-]+$/", $address)) {
    $_SESSION['message'] = 'Invalid address.';
    header("Location: profile.php");
    exit();
}

if (!preg_match("/^09\d{2}-\d{3}-\d{4}$/", $contact)) {
    $_SESSION['message'] = 'Contact number must be in the format 09XX-XXX-YYYY';
    header("Location: profile.php");
    exit();
}


$sql1 = "UPDATE user SET fname = '$fname', lname = '$lname', address = '$address', contact_number = '$contact' WHERE user_id = '$user_id'";
$result1 = mysqli_query($conn, $sql1);

if ($result1) {
    if (isset($_FILES['user_img']) && $_FILES['user_img']['error'] == 0) {
        $fileType = $_FILES['user_img']['type'];
        $uploadDir = '/kpopstore/user/uploads/'; 
        $targetFile = $uploadDir . basename($_FILES['user_img']['name']);

        if ($fileType == "image/png" || $fileType == "image/jpeg") {
            if (move_uploaded_file($_FILES['user_img']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $targetFile)) {
                // Update the image path in the database
                $imgPath = '../user/uploads/'  . basename($_FILES['user_img']['name']);
                $sql2 = "UPDATE user SET user_img = '$imgPath' WHERE user_id = '$user_id'";

                if (mysqli_query($conn, $sql2)) {
                    $_SESSION['user_image'] = $imgPath; 
                    $_SESSION['message'] = 'Profile updated successfully!';
                    header("Location: profile.php"); 
                    exit();
                } else {
                    $_SESSION['imageError'] = 'Error updating the image in the database.';
                    header("Location: profile.php");
                    exit();
                }
            } else {
                $_SESSION['imageError'] = "Error: Couldn't upload the file.";
                header("Location: profile.php");
                exit();
            }
        } else {
            $_SESSION['imageError'] = 'Invalid file type. Only JPG and PNG images are allowed.';
            header("Location: profile.php");
            exit();
        }
    } else {
        // No image uploaded, just redirect to the main page
        $_SESSION['message'] = 'Profile updated successfully!';
        header("Location: profile.php");
        exit();
    }
} else {
    // Error updating profile info in the database
    $_SESSION['message'] = "Error updating user info.";
    header("Location: profile.php");
    exit();
}