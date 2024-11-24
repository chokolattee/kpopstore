<?php
session_start();
include('./includes/config.php');
include('./includes/alert.php');

if (isset($_POST['type']) && $_POST['type'] == 'add' && isset($_POST['item_id'])) {
    $itemId = intval($_POST['item_id']);
    $itemQty = 1;

    $sql = "SELECT i.item_id, i.item_name, i.sell_price, s.quantity, ii.img_path 
            FROM item i 
            LEFT JOIN itemimg ii ON i.item_id = ii.item_id 
            LEFT JOIN stock s ON i.item_id = s.item_id 
            WHERE i.item_id = $itemId
            LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        if ($product['quantity'] <= 0) {
            $_SESSION['message'] = 'Product is not available.';
            header('Location: index.php');
            exit();
        }

        $productImage = "/kpopstore/item/" . $product['img_path'];

        $cart_item = array(
            'item_id'   => $product['item_id'],
            'item_name' => $product['item_name'],
            'item_price'=> $product['sell_price'],
            'item_qty'  => $itemQty,
            'item_img'  => $productImage
        );

        if (isset($_SESSION['cart_products'][$itemId])) {
            $_SESSION['cart_products'][$itemId]['item_qty'] += $itemQty;
        } else {
            $_SESSION['cart_products'][$itemId] = $cart_item;
        }

        header('Location: view_cart.php');
        exit();
    } else {
        $_SESSION['message'] = 'Product not found.';
        header('Location: index.php');
        exit();
    }
}
?>