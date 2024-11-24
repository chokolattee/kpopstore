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

$artist_id = (int) $_GET['id'];


$sql = "SELECT img_path FROM itemimg WHERE item_id IN (SELECT item_id FROM item WHERE artist_id = $artist_id)";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result_fetch);
$row = mysqli_fetch_assoc($result_fetch);

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


