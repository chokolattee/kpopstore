<?php
require('../includes/config.php');


$artist_id = (int)$_POST['artistId'];
$name = trim($_POST['artistName']);
$imgPath = null;

if (empty($name) || !preg_match("/^[a-zA-Z0-9\s,.-]{1,50}$/", $name)) {
    $_SESSION['nameError'] = 'Please input a valid artist name (up to 50 characters)';
    header("Location: edit.php?id={$artist_id}");
    exit();
}

if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
    $fileType = $_FILES['image']['type'];
    if ($fileType == "image/png" || $fileType == "image/jpeg") {
        $source = $_FILES['image']['tmp_name'];
        $target = '../artists/images/' . basename($_FILES['image']['name']);

        if (move_uploaded_file($source, $target)) {
            $imgPath = $target; 
        } else {
            die("Error: Couldn't upload the file.");
        }
    } else {
        $_SESSION['imageError'] = "Wrong file type. Only JPG and PNG files are allowed.";
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