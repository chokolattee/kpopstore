<?php
session_start();
include('./includes/config.php');

if (!isset($_SESSION['user_id']) || !isset($_POST['orderinfo_id'])) {
    $_SESSION['message'] = "Please log in and select an order to update.";
    header("Location: /kpopstore/user/login.php");
    exit();
}

$orderId = $_POST['orderinfo_id'];
$status = $_POST['status']; 
$userId = $_SESSION['user_id'];

$sql = "SELECT CONCAT(fname, ' ', lname) AS customerName, email FROM user WHERE user_id = $userId LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $customerName = $user['customerName']; 
    $email = $user['email'];
    $_SESSION['customerName'] = $customerName;
    $_SESSION['user_email'] = $email;
} else {
    echo "Error: Unable to fetch user information.";
    exit();
}


if (isset($_POST['orderinfo_id']) ){
    $orderinfo_id =  $_POST['orderinfo_id'];
    $sql = "UPDATE orderinfo SET status_id = 4 WHERE orderinfo_id = '$orderinfo_id' AND user_id = $userId";

    if (mysqli_query($conn, $sql)) {
        $sql = "UPDATE stock s 
        INNER JOIN orderdetails od ON s.item_id = od.item_ids 
        SET s.quantity = s.quantity + od.quantities 
        WHERE od.orderinfo_id = $orderinfo_id";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = 'Your order has been successfully cancelled.';
             // Fetch order details for the email notification
        $emailQuery = "SELECT u.email, 
        CONCAT(u.fname, ' ', u.lname) AS customerName, 
        od.total_amount, 
        od.date_placed, 
        od.date_shipped, 
        od.date_received,
        od.status 
 FROM orderdetails od
 JOIN user u ON u.user_id = od.user_id
 WHERE od.orderinfo_id = '$orderId'";

$emailResult = mysqli_query($conn, $emailQuery);

if ($emailData = mysqli_fetch_assoc($emailResult)) {

$_SESSION['customerName'] = $emailData['customerName'];
$_SESSION['user_email'] = $emailData['email'];
$_SESSION['orderinfo_id'] = $orderinfo_id;
$_SESSION['grandTotal'] = $emailData['total_amount'];
$_SESSION['status'] = $emailData['status'];
$_SESSION['date_placed'] = $emailData['date_placed'];
$_SESSION['date_shipped'] = $emailData['date_shipped'];
$_SESSION['date_received'] = $emailData['date_received'];

include('email_update.php');
} else {
$_SESSION['message'] .= ' However, failed to send an email notification.';
}

        } 
    } else {
        $_SESSION['message'] = 'Error cancelling your order. Please try again later.';
    }

    header("Location: /kpopstore/view_order.php");
    exit();
}