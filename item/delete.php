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