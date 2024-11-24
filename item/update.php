<?php
require('../includes/config.php');
include('../includes/alert.php');

$item_id = (int)$_POST['itemId'];
$sell_price = trim($_POST['sell_price']);
$description = strtolower(trim($_POST['description']));
$name = strtoupper(trim($_POST['name']));
$category_name = trim($_POST['category']);
$artist_name = trim($_POST['artist_name']);
$quantity = (int)$_POST['quantity']; 

if (empty($name) || !preg_match("/^[a-zA-Z0-9\s,.\-'\&\(\):]+$/", $name)) {
    $_SESSION['nameError'] = 'Please input a valid item name';
    header("Location: edit.php?id=$item_id");
    exit();
}

if (empty($description) || !preg_match("/^[a-zA-Z0-9\s,.\-'\&!\(\):\/\*“”‘’]+$/u", $description)) {
    $_SESSION['descError'] = 'Please input a valid item description';
    header("Location: edit.php?id=$item_id");
    exit();
}

if (empty($sell_price) || !is_numeric($sell_price)) {
    $_SESSION['sellError'] = 'Invalid product price format';
    header("Location: edit.php?id=$item_id");
    exit();
}

$sql_category = "SELECT category_id FROM category WHERE description = '{$category_name}'";
$result_category = mysqli_query($conn, $sql_category);
$category_row = mysqli_fetch_assoc($result_category);
$category_id = $category_row['category_id'];

$sql_artist = "SELECT artist_id FROM artists WHERE artist_name = '{$artist_name}'";
$result_artist = mysqli_query($conn, $sql_artist);
$artist_row = mysqli_fetch_assoc($result_artist);
$artist_id = $artist_row['artist_id'];

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
            if ($imageType == "image/png" || $imageType == "image/jpeg" || $imageType == "image/jpg") {
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
                exit();
            }
        } else {
            $_SESSION['imageError'] = "There was an error uploading the image.";
            header("Location: edit.php?id=" . $item_id);
            exit();
        }
    }
}

$sql_update = "UPDATE item 
               SET item_name = '$name', 
                   description = '$description', 
                   category_id = $category_id, 
                   artist_id = $artist_id, 
                   sell_price = $sell_price 
               WHERE item_id = $item_id";
$result_update = mysqli_query($conn, $sql_update);

foreach ($imagePaths as $imgPath) {
    $sql_img_check = "SELECT * FROM itemimg WHERE item_id = $item_id AND img_path = '{$imgPath}'";
    $result_img_check = mysqli_query($conn, $sql_img_check);
    if (mysqli_num_rows($result_img_check) == 0) {
        $sql_img = "INSERT INTO itemimg (item_id, img_path) VALUES ($item_id, '{$imgPath}')";
        mysqli_query($conn, $sql_img);
    }
}

$sql_stock = "UPDATE stock SET quantity = $quantity WHERE item_id = $item_id"; 
$result_stock = mysqli_query($conn, $sql_stock);

if ($result_update && $result_stock) {
    $_SESSION['success'] = 'Item updated successfully!';
    header("Location: index.php");
    exit();
} else {
    $_SESSION['error'] = 'Failed to update the item.';
    header("Location: edit.php?id=$item_id");
    exit();
}
?>