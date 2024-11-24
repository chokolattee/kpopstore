<?php
session_start();
require('../includes/config.php');
$review_id = $_POST['review_id'];

$sql= "SELECT img_path FROM reviewimg WHERE review_id = $review_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_num_rows($result);

if ($result && $row > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $image_path = $row['img_path'];
        if (file_exists($image_path)) {
            unlink($image_path); 
        }
    }
}

$sql_img = "DELETE FROM reviewimg WHERE review_id = $review_id";
$result_images = mysqli_query($conn, $sql_img);


$sql_item = "DELETE FROM review WHERE review_id = $review_id LIMIT 1";
$result_item = mysqli_query($conn, $sql_item);

if (mysqli_affected_rows($conn) > 0) {
    header("Location: index.php");
} 
?>