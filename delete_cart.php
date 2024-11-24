<?php
session_start();

if (isset($_SESSION["cart_products"]) && isset($_GET['id'])) {
    $item_id = $_GET['id']; 
    foreach ($_SESSION["cart_products"] as $key => $cart_item) {
        if ($cart_item["item_id"] == $item_id) {
            unset($_SESSION["cart_products"][$key]);  
            break;
        }
    }

    $_SESSION["cart_products"] = array_values($_SESSION["cart_products"]);

    header("Location: view_cart.php");
}
?>
