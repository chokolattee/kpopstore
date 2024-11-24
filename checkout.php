<?php
session_start();
include('./includes/header.php');
include('./includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please log in to complete the checkout.';
    header("Location: /kpopstore/user/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$shipping_id = isset($_SESSION['shipping_id']) ? $_SESSION['shipping_id'] : null;
$shipping_rate = isset($_SESSION['shipping_rate']) ? $_SESSION['shipping_rate'] : 0;
$total = 0;

$sql = "SELECT CONCAT(fname, ' ', lname) AS customerName, email FROM user WHERE user_id = $userId LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $customerName = $user['customerName']; // Added to prevent undefined variable
    $email = $user['email'];
    $_SESSION['customerName'] = $customerName;
    $_SESSION['user_email'] = $email; // Save email for use in email.php
} else {
    echo "Error: Unable to fetch user information.";
    exit();
}


if (isset($_SESSION['cart_products'])) {
    foreach ($_SESSION['cart_products'] as $cart_item) {
        $product_qty = $cart_item["item_qty"];
        $product_price = $cart_item["item_price"];
        $total += $product_price * $product_qty;
    }
}

$grandTotal = $total + $shipping_rate;
$_SESSION['grandTotal'] = $grandTotal;

try {
    mysqli_query($conn, 'START TRANSACTION');

    $status_id = 1; 
    $sql_order = "INSERT INTO orderinfo(user_id, shipping_id, date_placed, status_id)
                  VALUES (?, ?, NOW(), ?)";
    $stmt_order = mysqli_prepare($conn, $sql_order);
    mysqli_stmt_bind_param($stmt_order, 'iii', $userId, $shipping_id, $status_id);
    mysqli_stmt_execute($stmt_order);
    $orderinfo_id = mysqli_insert_id($conn);

    $_SESSION['orderinfo_id'] = $orderinfo_id;

    // Prepare the orderline statement outside the loop
    $sql_orderline = "INSERT INTO orderline(orderinfo_id, item_id, quantity) VALUES (?, ?, ?)";
    $stmt_orderline = mysqli_prepare($conn, $sql_orderline);

    // Prepare the stock update statement outside the loop
    $sql_stock = "UPDATE stock SET quantity = quantity - ? WHERE item_id = ?";
    $stmt_stock = mysqli_prepare($conn, $sql_stock);

    foreach ($_SESSION['cart_products'] as $cart_item) {
        $item_id = $cart_item['item_id'];
        $quantity = $cart_item['item_qty'];

        // Bind and execute the orderline statement
        mysqli_stmt_bind_param($stmt_orderline, 'iii', $orderinfo_id, $item_id, $quantity);
        mysqli_stmt_execute($stmt_orderline);

        // Bind and execute the stock update statement
        mysqli_stmt_bind_param($stmt_stock, 'ii', $quantity, $item_id);
        mysqli_stmt_execute($stmt_stock);
    }

    mysqli_commit($conn);

    // Clear session variables
    unset($_SESSION['cart_products']);
    unset($_SESSION['region']);
    unset($_SESSION['shipping_rate']);
    unset($_SESSION['shipping_id']);

    include('email_confirm.php');
    header("Location: view_order.php");
    exit();

} catch (mysqli_sql_exception $e) {
    mysqli_rollback($conn);
    echo $e->getMessage();
}