<?php
session_start();
include('../includes/config.php');

if (isset($_POST['submit'])) {
    $name = trim($_POST['artistName']);

    if (!preg_match("/^[a-zA-Z0-9\s,.-]{1,50}$/", $name) && empty($name)) {
        $_SESSION['nameError'] = 'Please input an artist name up to 50 characters';
        header("Location: create.php");
        exit();
    }

    if (isset($_FILES['img_path']) && $_FILES['img_path']['error'] == 0) {
        $fileType = $_FILES['img_path']['type'];
        if ($fileType == "image/jpeg" || $fileType == "image/jpg" || $fileType == "image/png") {
            $source = $_FILES['img_path']['tmp_name'];
            $target = '../artists/images/' . basename($_FILES['img_path']['name']); 
            if (move_uploaded_file($source, $target)) {
                $sql = "INSERT INTO artists (artist_name, img_path) VALUES ('$name', '$target')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    header("Location: index.php");
                    exit();
                } else {
                    $_SESSION['dbError'] = "Database error: Could not insert artist.";
                }
            } else {
                $_SESSION['imageError'] = "File upload failed. Please try again.";
            }
        } else {
            $_SESSION['imageError'] = "Invalid file type. Only JPEG and PNG files are allowed.";
        }
    } else {
        $_SESSION['imageError'] = "No file uploaded.";
    }


    header("Location: create.php");
    exit();
}
?>