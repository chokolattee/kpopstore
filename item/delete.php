<?php
require('../includes/config.php');
$item_id = (int) $_GET['id'];

$sql_stock = "DELETE FROM stock WHERE item_id = $item_id LIMIT 1";
$result_stock = mysqli_query($conn, $sql_stock);

$sql_item = "DELETE FROM item WHERE item_id = $item_id LIMIT 1";
$result_item = mysqli_query($conn, $sql_item);

if (mysqli_affected_rows($conn) > 0) {
    header("Location: index.php");
    exit();
} else {
    echo "Error deleting item: " . mysqli_error($conn);
}
?>