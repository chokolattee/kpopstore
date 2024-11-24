<?php
require('../includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please log in to access resources';
    header("Location: /kpopstore/user/login.php");
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id' AND r.role_id = 1 LIMIT 1";
$result= mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['message'] = 'You must be logged in as admin to access this page.';
    header("Location: /kpopstore/user/login.php");
    exit();
}


$artist_id = (int)$_POST['artistId'];
$name = trim($_POST['artistName']);
$imgPath = null;

if (!preg_match("/^[a-zA-Z0-9\s,.-]{1,50}$/", $name) && empty($name)) {
    $_SESSION['nameError'] = 'Please input an artist name up to 50 characters';
    header("Location: edit.php");
    exit();
}

if (isset($_FILES['image'])) {
    $fileType = $_FILES['image']['type'];
    if ($fileType  == "image/png" || $fileType  == "image/jpeg") {
        $source = $_FILES['image']['tmp_name'];
        $target = '../artists/images/' . basename($_FILES['image']['name']);

        if (move_uploaded_file($source, $target)) {
            $imgPath = $target; 
        } else {
            die("Error: Couldn't copy the uploaded file.");
        }
    } else {
        $_SESSION['imageError'] = "Wrong file type. Only JPG and PNG are allowed.";
        header("Location: edit.php?id={$artist_id}");
        exit();
    }
}

$sql = "UPDATE artists SET artist_name = '{$name}'" . 
($imgPath ? ", img_path = '{$imgPath}'" : "") . 
       " WHERE artist_id = $artist_id";
$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: index.php");
    exit();
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
?>