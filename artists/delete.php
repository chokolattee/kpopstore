<?php
require('../includes/config.php');
$artist_id = (int) $_GET['id'];


$sql_get = "SELECT img_path FROM artists WHERE artist_id = $artist_id";
$result_fetch = mysqli_query($conn, $sql_get);

if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
    while ($row = mysqli_fetch_assoc($result_fetch)) {
        $image_path = $row['img_path'];
        if (file_exists($image_path)) {
            unlink($image_path); 
        }
    }
}

$sql_img = "DELETE FROM itemimg WHERE item_id IN (SELECT item_id FROM item WHERE artist_id = $artist_id)";
$result_img = mysqli_query($conn, $sql_img);

$sql = "DELETE FROM item WHERE artist_id = $artist_id"; 
$result = mysqli_query($conn, $sql);

$sql1 = "DELETE FROM artists WHERE artist_id = $artist_id LIMIT 1";
$result1 = mysqli_query($conn, $sql1);

if ($result1 && mysqli_affected_rows($conn) > 0) {
    header("Location: index.php ");
}  


