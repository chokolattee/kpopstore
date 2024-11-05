<?php
require('../includes/config.php');

$item_id = (int)$_POST['itemId'];
$cost =  trim($_POST['cost_price']);
$sell = trim($_POST['sell_price']);
$desc = trim($_POST['description']);
$category = strtolower(trim($_POST['category']));
$qty = (int)$_POST['quantity'];
$artist_id = (int)$_POST['artist_id']; 

$sql = "SELECT img_path FROM itemimg WHERE item_id = $item_id";
$result = mysqli_query($conn, $sql);

$currentImgPaths = [];

while ($row = mysqli_fetch_assoc($result)) {
    $currentImgPaths[] = $row['img_path'];
}

foreach ($currentImgPaths as $imgPath) {
    if (file_exists($imgPath)) {
        unlink($imgPath); 
    }
}

$sql_oldimg = "DELETE FROM itemimg WHERE item_id = $item_id";
mysqli_query($conn, $sql_oldimg);

$imagePaths = [];
if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
    // Loop through each uploaded file
    for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
        if ($_FILES['images']['error'][$i] == UPLOAD_ERR_OK) {
            $imageType = $_FILES['images']['type'][$i];
            if ($imageType == "image/png" || $imageType == "image/jpeg") {
                $source = $_FILES['images']['tmp_name'][$i];
                $target = '../item/images/' . basename($_FILES['images']['name'][$i]);

                // Move the uploaded image to the target directory
                if (move_uploaded_file($source, $target)) {
                    $imagePaths[] = $target; // Add image path to the array
                } else {
                    die("Error: Couldn't copy the uploaded file.");
                }
            } else {
                $_SESSION['imageError'] = "Only JPG and PNG images are allowed.";
                header("Location: edit.php?id=" . $item_id); // Redirect back with error
                exit;
            }
        } else {
            $_SESSION['imageError'] = "There was an error uploading the image.";
            header("Location: edit.php?id=" . $item_id); // Redirect back with error
            exit;
        }
    }
} else {
    $_SESSION['imageError'] = "Please upload at least one image file.";
    header("Location: edit.php?id=" . $item_id); // Redirect back with error
    exit;
}

$sql = "UPDATE item SET description = '{$desc}', category = '{$category}', cost_price = '{$cost}', sell_price = '{$sell}', artist_id = '{$artist_id}' WHERE item_id = $item_id";
$result = mysqli_query($conn, $sql);

foreach ($imagePaths as $imgPath) {
    $sql_img = "INSERT INTO itemimg (item_id, img_path) VALUES ($item_id, '{$imgPath}')";
    mysqli_query($conn, $sql_img);
}


$sql1 = "UPDATE stock SET quantity = $qty WHERE item_id = $item_id";
$result1 = mysqli_query($conn, $sql1);


if ($result && $result1) {
    header("Location: index.php");
} 
?>
