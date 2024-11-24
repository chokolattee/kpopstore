<?php
session_start();
include('./includes/config.php');

if (isset($_POST['product_qty'])) {
    foreach ($_POST['product_qty'] as $itemId => $newQty) {

        if ($newQty < 1) {
            $newQty = 1;
        }


        $itemId = intval($itemId); // Sanitize item ID
        $sql = "SELECT quantity FROM stock WHERE item_id = $itemId";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $stock = intval($row['quantity']); // Available stock for the item

            if ($newQty > $stock) {
                $_SESSION['message'] = "Stock is currently unavailable for this item. Adjusted to maximum available stock.";
                $newQty = $stock;
            }

            if (isset($_SESSION['cart_products'][$itemId])) {
                $_SESSION['cart_products'][$itemId]['item_qty'] = $newQty;

                $productPrice = $_SESSION['cart_products'][$itemId]['item_price'];
                $subtotal = $newQty * $productPrice;
                $_SESSION['cart_products'][$itemId]['item_subtotal'] = $subtotal;
            }
        } else {
            $_SESSION['message'] = "Error fetching stock for product ID: $itemId.";
        }
    }

    $total = 0;
    foreach ($_SESSION['cart_products'] as $cart_item) {
        $total += $cart_item['item_subtotal'];
    }
    $_SESSION['cart_total'] = $total;

    header('Location: view_cart.php');
    exit();
}
?>