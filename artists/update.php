<?php
require('../includes/config.php');

$artist_id = (int)$_POST['artistId'];
$name = trim($_POST['artistName']);
$imgPath = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    if ($_FILES['image']['type'] == "image/png" || $_FILES['image']['type'] == "image/jpeg") {
        $source = $_FILES['image']['tmp_name'];
        $target = '../artists/images/' . basename($_FILES['image']['name']);

        if (move_uploaded_file($source, $target)) {
            $imgPath = $target; // Save the new image path
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
