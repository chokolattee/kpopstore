<?php
require('../includes/config.php');

$item_id = (int)$_POST['itemId'];
$cost = trim($_POST['cost_price']);
$sell = trim($_POST['sell_price']);
$desc = trim($_POST['description']);
$name = trim($_POST['name']);
$category = strtolower(trim($_POST['category']));
$qty = $_POST['quantity'];
$artist_id = $_POST['artist_id']; 

$sql = "SELECT img_path FROM itemimg WHERE item_id = $item_id";
$result = mysqli_query($conn, $sql);

$currentImgPaths = [];

while ($row = mysqli_fetch_assoc($result)) {
    $currentImgPaths[] = $row['img_path'];
}

$imagePaths = $currentImgPaths;

$imageCount = count($_FILES['images']['name']);

if (isset($_FILES['images']) && $imageCount > 0 && $_FILES['images']['name'][0] != '') {
    foreach ($currentImgPaths as $imgPath) {
        if (file_exists($imgPath)) {
            unlink($imgPath); 
        }
    }

    $sql_oldimg = "DELETE FROM itemimg WHERE item_id = $item_id";
    mysqli_query($conn, $sql_oldimg);

    $imagePaths = [];  
    for ($i = 0; $i < $imageCount; $i++) {
        if ($_FILES['images']['error'][$i] == UPLOAD_ERR_OK) {
            $imageType = $_FILES['images']['type'][$i];
            if ($imageType == "image/png" || $imageType == "image/jpeg") {
                $source = $_FILES['images']['tmp_name'][$i];
                $target = '../item/images/' . basename($_FILES['images']['name'][$i]);

                if (move_uploaded_file($source, $target)) {
                    $imagePaths[] = $target; 
                } else {
                    die("Error: Couldn't copy the uploaded file.");
                }
            } else {
                $_SESSION['imageError'] = "Only JPG and PNG images are allowed.";
                header("Location: edit.php?id=" . $item_id); 
            }
        } else {
            $_SESSION['imageError'] = "There was an error uploading the image.";
            header("Location: edit.php?id=" . $item_id); 
        }
    }
}

$sql_item = "UPDATE item SET item_name = '{$name}', description = '{$desc}', category = '{$category}', cost_price = '{$cost}', sell_price = '{$sell}', artist_id = '{$artist_id}' WHERE item_id = $item_id";
$result_item = mysqli_query($conn, $sql_item);

foreach ($imagePaths as $imgPath) {
    $sql_img_check = "SELECT * FROM itemimg WHERE item_id = $item_id AND img_path = '{$imgPath}'";
    $result_img_check = mysqli_query($conn, $sql_img_check);
    if (mysqli_num_rows($result_img_check) == 0) {
        $sql_img = "INSERT INTO itemimg (item_id, img_path) VALUES ($item_id, '{$imgPath}')";
        mysqli_query($conn, $sql_img);
    }
}

$sql_stock = "UPDATE stock SET quantity = $qty WHERE item_id = $item_id";
$result_stock = mysqli_query($conn, $sql_stock);

if ($result_item && $result_stock) {
    header("Location: index.php");
} 
?>
