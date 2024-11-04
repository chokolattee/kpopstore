<?php
require('../includes/config.php');

$item_id = (int)$_POST['itemId'];
$cost =  trim($_POST['cost_price']);
$sell = trim($_POST['sell_price']);
$desc = trim($_POST['description']);
$category = strtolower(trim($_POST['category']));
$qty = (int)$_POST['quantity'];
$artist_id = (int)$_POST['artist_id']; 

$sql = "SELECT img_path FROM item WHERE item_id = $item_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$currentImgPath = $row['img_path'];

$imgPath = $currentImgPath;

if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    if ($_FILES['image']['type'] == "image/png" || $_FILES['image']['type'] == "image/jpeg") {
        $source = $_FILES['image']['tmp_name'];
        $target = '../item/images/' . basename($_FILES['image']['name']);

        if (move_uploaded_file($source, $target)) {
            $imgPath = $target;
        } else {
            die("Error: Couldn't copy the uploaded file.");
        }
    } else {
        $_SESSION['imageError'] = "Wrong file type. Only JPG and PNG are allowed.";
        header("Location: edit.php?id={$item_id}");
    }
}


$sql = "UPDATE item SET description = '{$desc}', category = '{$category}', cost_price = '{$cost}', sell_price = '{$sell}', img_path = '{$imgPath}', artist_id = '{$artist_id}' WHERE item_id = $item_id";

$result = mysqli_query($conn, $sql);

$sql1 = "UPDATE stock SET quantity = $qty WHERE item_id = $item_id";
$result1 = mysqli_query($conn, $sql1);


if ($result & $result1) {
    header("Location: index.php");
} 
?>
