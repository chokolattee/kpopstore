<?php
session_start();
include('../includes/config.php');

if (isset($_POST['submit'])) {
    $sell = trim($_POST['sell_price']);
    $itemname = strtoupper(trim($_POST['itemname']));
    $desc = strtolower(trim($_POST['description']));
    $category = strtolower(trim($_POST['category']));
    $qty = (int)$_POST['quantity'];
    $artist_id = (int)$_POST['artist_id'];

    if (empty($itemname) || !preg_match("/^[a-zA-Z0-9\s,.\-'\&\(\):]+$/", $itemname)) {
        $_SESSION['nameError'] = 'Please input a valid item name.';
        header("Location: create.php");
        exit();
    }

    if (empty($desc) || !preg_match("/^[a-zA-Z0-9\s,.\-'\&!\(\):\/\*\"“”‘’]+$/u", $desc)) {
        $_SESSION['descError'] = 'Please input a valid item description.';
        header("Location: create.php");
        exit();
    }

    if (empty($sell) || !is_numeric($sell)) {
        $_SESSION['sellError'] = 'Error in product price format.';
        header("Location: create.php");
        exit();
    }

    if (!in_array($category, ['album', 'merchandise'])) {
        $_SESSION['categoryError'] = 'Invalid category. Please select either "album" or "merchandise".';
        header("Location: create.php");
        exit();
    }

    if (empty($artist_id)) {
        $_SESSION['artistError'] = 'Please select an artist.';
        header("Location: create.php");
        exit();
    }

    $sql_category = "SELECT category_id FROM category WHERE LOWER(description) = '{$category}' LIMIT 1";
    $result_category = mysqli_query($conn, $sql_category);

    if ($result_category && mysqli_num_rows($result_category) > 0) {
        $category_row = mysqli_fetch_assoc($result_category);
        $category_id = $category_row['category_id'];
    } else {
        $_SESSION['categoryError'] = 'Invalid category.';
        header("Location: create.php");
        exit();
    }

    $imagePaths = [];
    if (isset($_FILES['img_path']) && !empty($_FILES['img_path']['name'][0])) {
        foreach ($_FILES['img_path']['name'] as $key => $name) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $fileType = $_FILES['img_path']['type'][$key];

            if (in_array($fileType, $allowedTypes)) {
                $source = $_FILES['img_path']['tmp_name'][$key];
                $target = '../item/images/' . basename($name);

                if (move_uploaded_file($source, $target)) {
                    $imagePaths[] = $target;
                } else {
                    $_SESSION['imageError'] = "Couldn't copy the image file.";
                    header("Location: create.php");
                    exit();
                }
            } else {
                $_SESSION['imageError'] = "Wrong file type. Only JPG and PNG files are allowed.";
                header("Location: create.php");
                exit();
            }
        }
    } else {
        $_SESSION['imageError'] = "Please upload at least one image file.";
        header("Location: create.php");
        exit();
    }

    $sql = "INSERT INTO item (item_name, description, category_id, sell_price, artist_id) 
            VALUES ('{$itemname}', '{$desc}', '{$category_id}', '{$sell}', '{$artist_id}')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $_SESSION['itemSuccess'] = 'Item details saved successfully!';
        $itemId = mysqli_insert_id($conn);

        foreach ($imagePaths as $imgPath) {
            $sql_img = "INSERT INTO itemimg (item_id, img_path) VALUES ('{$itemId}', '{$imgPath}')";
            $result_img = mysqli_query($conn, $sql_img);

            if (!$result_img) {
                $_SESSION['dbError'] = 'Failed to save image details.';
                header("Location: create.php");
                exit();
            }
        }

        $sql_stock = "INSERT INTO stock (item_id, quantity) VALUES ('{$itemId}', '{$qty}')";
        $result_stock = mysqli_query($conn, $sql_stock);

        if ($result_stock) {
            $_SESSION['success'] = 'Item created successfully!';
            unset($_SESSION['nameError'], $_SESSION['descError'], $_SESSION['sellError'], $_SESSION['categoryError'], $_SESSION['artistError'], $_SESSION['imageError']);
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['dbError'] = 'Failed to save stock details.';
            header("Location: create.php");
            exit();
        }
    } else {
        $_SESSION['dbError'] = 'Failed to save item details.';
        header("Location: create.php");
        exit();
    }
}
?>