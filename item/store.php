<?php
session_start();
include('../includes/config.php');
$_SESSION['cost'] = trim($_POST['cost_price']);
$_SESSION['sell'] = trim($_POST['sell_price']);
$_SESSION['desc'] = trim($_POST['description']);
$_SESSION['category'] = trim($_POST['category']);
$_SESSION['qty'] = $_POST['quantity'];
$_SESSION['artist'] = $_POST['artist_id'];


if (isset($_POST['submit'])) {
    $cost = trim($_POST['cost_price']);
    $sell = trim($_POST['sell_price']);
    $desc = trim($_POST['description']);
    $category = strtolower(trim($_POST['category']));
    $qty = $_POST['quantity'];
    $artist_id = (int) $_POST['artist_id'];

    if (empty($desc)) {
        $_SESSION['descError'] = 'Please input a Product description';
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

    if (isset($_FILES['img_path'])) {
        if ($_FILES['img_path']['type'] == "image/jpeg" || $_FILES['img_path']['type'] == "image/jpg" || $_FILES['img_path']['type'] == "image/png") {
            $source = $_FILES['img_path']['tmp_name'];
            $target = '../item/images/' . basename($_FILES['img_path']['name']);

    
            if (!move_uploaded_file($source, $target)) {
                $_SESSION['imageError'] = "Couldn't copy the image file.";
                header("Location: create.php");
            }
        } else {
            $_SESSION['imageError'] = "Wrong file type. Only JPG and PNG files are allowed.";
            header("Location: create.php");
        }
    } else {
        $_SESSION['imageError'] = "Please upload an image file.";
        header("Location: create.php");
    }

    $sql = "INSERT INTO item(description, category, cost_price, sell_price, img_path, artist_id) VALUES('{$desc}', '{$category}', '{$cost}', '{$sell}', '{$target}', '{$artist_id}')";
    $result = mysqli_query($conn, $sql);


    $q_stock = "INSERT INTO stock(item_id, quantity) VALUES(LAST_INSERT_ID(), '{$qty}')";
    $result2 = mysqli_query($conn, $q_stock);

    if($result && $result2) {
        
        header("Location: index.php");
    }
 
}