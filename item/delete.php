<?php
require('../includes/config.php');
$item_id = (int) $_GET['id'];

$sql= "SELECT img_path FROM itemimg WHERE item_id = $item_id";
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

$sql_img = "DELETE FROM itemimg WHERE item_id = $item_id";
$result_images = mysqli_query($conn, $sql_img);

$sql_stock = "DELETE FROM stock WHERE item_id = $item_id LIMIT 1";
$result_stock = mysqli_query($conn, $sql_stock);

$sql_item = "DELETE FROM item WHERE item_id = $item_id LIMIT 1";
$result_item = mysqli_query($conn, $sql_item);

if (mysqli_affected_rows($conn) > 0) {
    header("Location: index.php");
} 
?>