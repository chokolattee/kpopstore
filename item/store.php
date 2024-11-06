<?php
session_start();
include('../includes/config.php');

$_SESSION['cost'] = trim($_POST['cost_price']);
$_SESSION['sell'] = trim($_POST['sell_price']);
$_SESSION['desc'] = trim($_POST['description']);
$_SESSION['name'] = trim($_POST['itemname']);
$_SESSION['category'] = trim($_POST['category']);
$_SESSION['qty'] = $_POST['quantity'];
$_SESSION['artist'] = $_POST['artist_id'];


if (isset($_POST['submit'])) {
    $cost = trim($_POST['cost_price']);
    $sell = trim($_POST['sell_price']);
    $itemname = trim($_POST['itemname']);
    $desc = trim($_POST['description']);
    $category = strtolower(trim($_POST['category']));
    $qty = $_POST['quantity'];
    $artist_id = (int) $_POST['artist_id'];

    if (empty($itemname)) {
        $_SESSION['nameError'] = 'Please input an item name';
        header("Location: create.php");
    }

    if (empty($desc)) {
        $_SESSION['descError'] = 'Please input a item description';
        header("Location: create.php");
    }

    if (empty($cost) || (! is_numeric($cost))) {
        $_SESSION['costError'] = 'error product price format';
        header("Location: create.php");
    }

    if (!in_array($category, ['album', 'merchandise'])) {
        $_SESSION['categoryError'] = 'Invalid category. Please select either "album" or "merchandise".';
        header("Location: create.php");
    }

    if (empty($artist_id)) {
        $_SESSION['artistError'] = 'Please select an artist.';
        header("Location: create.php");
    }
    $imagePaths = [];
    if (isset($_FILES['img_path']) && !empty($_FILES['img_path']['name'][0])) {
        foreach ($_FILES['img_path']['name'] as $key => $name) {
            if ($_FILES['img_path']['type'][$key] == "image/jpeg" || $_FILES['img_path']['type'][$key] == "image/png") {
                $source = $_FILES['img_path']['tmp_name'][$key];
                $target = '../item/images/' . basename($name);

                if (move_uploaded_file($source, $target)) {
                    $imagePaths[] = $target; 
                } else {
                    $_SESSION['imageError'] = "Couldn't copy the image file.";
                    header("Location: create.php");
                }
            } else {
                $_SESSION['imageError'] = "Wrong file type. Only JPG and PNG files are allowed.";
                header("Location: create.php");
            }
        }
    } else {
        $_SESSION['imageError'] = "Please upload at least one image file.";
        header("Location: create.php");
    }

    $sql = "INSERT INTO item(item_name, description, category, cost_price, sell_price, artist_id) 
            VALUES('{$itemname}', '{$desc}', '{$category}', '{$cost}', '{$sell}', '{$artist_id}')";
    $result = mysqli_query($conn, $sql);

    $itemId = mysqli_insert_id($conn);

foreach ($imagePaths as $imgPath) {
    $sql_img = "INSERT INTO itemimg(item_id, img_path) VALUES('{$itemId}', '{$imgPath}')";
    $result1 = mysqli_query($conn, $sql_img);
}

    $q_stock = "INSERT INTO stock(item_id, quantity) VALUES('{$itemId}', '{$qty}')";
    $result2 = mysqli_query($conn, $q_stock);

    if($result && $result1 && $result2) {
        header("Location: index.php");
    }
 
}