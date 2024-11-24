<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_email'], $_SESSION['orderinfo_id'], $_SESSION['grandTotal'])) {
    echo "Error: Missing email data.";
    exit();
}

$customerName = $_SESSION['customerName'];
$user_email = $_SESSION['user_email'];
$orderinfo_id = $_SESSION['orderinfo_id'];
$grandTotal = $_SESSION['grandTotal'];
$status = $_SESSION['status'];
$date_placed = $_SESSION['date_placed'];
$date_shipped = isset($_SESSION['date_shipped']) ? $_SESSION['date_shipped'] : "Not shipped yet";
$date_received = isset($_SESSION['date_received']) ? $_SESSION['date_received'] : "Not received yet";

$order_details = '';
$subtotals = [];
$total_order_amount = 0;

$sql = "SELECT items, quantities, address, sell_prices, status, date_placed, date_shipped, date_received, subtotals, shipping_rate, total_amount
        FROM orderdetails
        WHERE orderinfo_id = $orderinfo_id"; 

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $item_names = explode(',', $row['items']); // Exploding items (comma-separated)
        $quantities = explode(',', $row['quantities']); // Exploding quantities (comma-separated)
        $sell_prices = explode(',', $row['sell_prices']); // Exploding prices (comma-separated)
        $rate = $row['shipping_rate']; 
        $status = $row['status']; 
        $address = $row['address'];
        $date_placed = $row['date_placed'];
        $total_amount = $row['total_amount'];

        // Ensure that the lengths of items, quantities, and prices arrays match
        $item_subtotal = 0;
        for ($i = 0; $i < count($item_names); $i++) {
            $item_name = $item_names[$i];
            $quantity = (int) $quantities[$i];
            $sell_price = (float) $sell_prices[$i];

            // Calculate the subtotal for each item
            $item_subtotal += $quantity * $sell_price;

            // Add item details to the table
            $order_details .= "
                <tr>
                    <td style='border: 1px solid #ddd; padding: 8px;'>{$item_name}</td>
                    <td style='border: 1px solid #ddd; padding: 8px;'>{$quantity}</td>
                    <td style='border: 1px solid #ddd; padding: 8px;'>₱" . number_format($sell_price, 2) . "</td>
                    <td style='border: 1px solid #ddd; padding: 8px;'>₱" . number_format($quantity * $sell_price, 2) . "</td>
                </tr>
            ";
        }

        $subtotals[] = $item_subtotal;
        $total_order_amount += $item_subtotal; 
    }
} else {
    $order_details = "<tr><td colspan='4' style='text-align: center;'>No order details found.</td></tr>";
}

// Sum the subtotals explicitly
$total_subtotal = array_sum($subtotals); // Sum of all item subtotals

// Adding shipping rate to the total amount
$total_amount_with_shipping = $total_subtotal + $rate; // Include shipping in the total amount

try {
    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'hallyu0401@gmail.com'; 
    $mail->Password = 'peki ovkm vycz epkd'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Sender and recipient
    $mail->setFrom('hallyu0401@gmail.com', 'Hallyu - Kpop Store'); 
    $mail->addAddress($user_email, $customerName);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = "Order Update for Order ID:{$orderinfo_id}";
    $mail->Body = "
    <h1>Order Update</h1>
    <p>Dear {$customerName},</p>
    <p>Your order has been updated. Below are the details:</p>
    <p><strong>Order ID:</strong> {$orderinfo_id}</p>
    <p><strong>Status:</strong> {$status}</p>
    <p><strong>Date Placed:</strong> {$date_placed}</p>
        <p><strong>Shipping Address:</strong> {$address}</p>
    <h3>Order Details:</h3>
    <table border='1' cellpadding='5' cellspacing='0' style='width: 100%; border-collapse: collapse;'>
        <thead>
            <tr>
                <th style='border: 1px solid #ddd; padding: 8px;'>Item</th>
                <th style='border: 1px solid #ddd; padding: 8px;'>Quantity</th>
                <th style='border: 1px solid #ddd; padding: 8px;'>Price (each)</th>
                <th style='border: 1px solid #ddd; padding: 8px;'>Subtotal Per Item</th>
            </tr>
        </thead>
        <tbody>
            {$order_details}
        </tbody>
    </table>
    <p><strong>Total Subtotal:</strong> ₱" . number_format($total_subtotal, 2) . "</p>
    <p><strong>Shipping Rate:</strong> ₱" . number_format($rate, 2) . "</p>
    <p><strong>Grand Total (including shipping):</strong> ₱" . number_format($total_amount_with_shipping, 2) . "</p>
    <p><strong>Date Shipped:</strong> {$date_shipped}</p>
    <p><strong>Date Received:</strong> {$date_received}</p>
<p>Thank you for shopping with us! If you have any questions, please contact our support team.</p>
    <p>Best regards,<br>Hallyu - Kpop Store Team</p>
";

    $mail->send();
    $_SESSION['message'] = "Order update email sent successfully!";
    header("Location: view_order.php");
} catch (Exception $e) {
    error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    echo "Error: Unable to send email. Please contact support.";
}
?>