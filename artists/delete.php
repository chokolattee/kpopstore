<?php
require('../includes/config.php');
$artist_id = (int) $_GET['id'];


$sql = "SELECT img_path FROM itemimg WHERE item_id IN (SELECT item_id FROM item WHERE artist_id = $artist_id)";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);

if ($result && $count > 0) {
    while ($row) {
        $image_path = $row['img_path'];
        if (file_exists($image_path)) {
            unlink($image_path); 
        }
    }
}

$sql_img = "DELETE FROM itemimg WHERE item_id IN (SELECT item_id FROM item WHERE artist_id = $artist_id)";
$result_img = mysqli_query($conn, $sql_img);

$sql_stock = "DELETE FROM stock WHERE item_id IN (SELECT item_id FROM item WHERE artist_id = $artist_id)";
$result_stock = mysqli_query($conn, $sql_stock);

$sql_item = "DELETE FROM item WHERE artist_id = $artist_id LIMIT 1";
$result_item = mysqli_query($conn, $sql_item);

$sql1 = "DELETE FROM artists WHERE artist_id = $artist_id LIMIT 1";
$result1 = mysqli_query($conn, $sql1);

$selectedrow = mysqli_affected_rows($conn);

if ($selectedrow  > 0) {
    header("Location: index.php ");
}  



