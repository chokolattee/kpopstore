<?php
session_start();
include("../includes/config.php");

if (!isset($_SESSION['user_id']) || !isset($_POST['orderinfo_id'])) {
    $_SESSION['message'] = "Please log in and select an order to update.";
    header("Location: /kpopstore/user/login.php");
    exit();
}

$orderId = $_POST['orderinfo_id'];
$status = $_POST['status']; 
$dateShipped = $_POST['date_shipped'];

// Fetch the status_id for the provided status
$sql = "SELECT status_id, status FROM status WHERE status = '$status'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching status_id: " . mysqli_error($conn));  // Debugging if the status query fails
}

$row = mysqli_fetch_assoc($result);
$statusId = $row['status_id'];

$updateQuery = "UPDATE orderinfo SET status_id = '$statusId'";

// If the status is "Shipped", include the shipping date
if ($status === 'Shipped' && !empty($dateShipped)) {
    $dateShipped = date('Y-m-d', strtotime($dateShipped));  // Ensure the date is properly formatted
    $updateQuery .= ", date_shipped = '$dateShipped'";
} 

$updateQuery .= " WHERE orderinfo_id = '$orderId'";


$updateResult = mysqli_query($conn, $updateQuery);

if ($updateResult) {
    $_SESSION['message'] = "Order status updated successfully!";
} else {
    $_SESSION['message'] = "Error updating order status: " . mysqli_error($conn);
}

$emailQuery = "SELECT 
    u.email, 
    CONCAT(u.fname, ' ', u.lname) AS customerName, 
    od.total_amount, 
    od.date_placed, 
    od.date_shipped, 
    od.status 
FROM orderdetails od
JOIN user u ON u.user_id = od.user_id
WHERE od.orderinfo_id = '$orderId'";

$emailResult = mysqli_query($conn, $emailQuery);

if ($emailResult && mysqli_num_rows($emailResult) > 0) {
    $emailData = mysqli_fetch_assoc($emailResult);

    $_SESSION['customerName'] = $emailData['customerName'];
    $_SESSION['user_email'] = $emailData['email'];
    $_SESSION['orderinfo_id'] = $orderId;
    $_SESSION['grandTotal'] = $emailData['total_amount'];
    $_SESSION['status'] = $emailData['status'];
    $_SESSION['date_placed'] = $emailData['date_placed'];
    $_SESSION['date_shipped'] = $emailData['date_shipped'];

    // Include the email_update.php script to send the email
    include('../email_update.php');
} else {
    $_SESSION['message'] = "Order updated, but failed to fetch email data.";
}

header("Location: /kpopstore/admin/orders.php");
exit();