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

$user_email = $_SESSION['user_email'];
$orderinfo_id = $_SESSION['orderinfo_id'];
$grandTotal = $_SESSION['grandTotal'];
$customerName = isset($_SESSION['customerName']) ? $_SESSION['customerName'] : 'Customer';

$order_details = '';
$subtotals = [];
$total_order_amount = 0;

$sql = "SELECT items, quantities, address, sell_prices, status, date_placed, date_shipped, date_received, subtotals, shipping_rate, total_amount
        FROM orderdetails
        WHERE orderinfo_id = $orderinfo_id"; 

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $item_names = explode(',', $row['items']);
        $quantities = explode(',', $row['quantities']);
        $sell_prices = explode(',', $row['sell_prices']); 
        $rate = $row['shipping_rate']; 
        $status = $row['status']; 
        $address = $row['address'];
        $date_placed = $row['date_placed'];
        $total_amount = $row['total_amount'];

        $item_subtotal = 0;
        for ($i = 0; $i < count($item_names); $i++) {
            $item_name = $item_names[$i];
            $quantity = (int) $quantities[$i];
            $sell_price = (float) $sell_prices[$i];

            $item_subtotal += $quantity * $sell_price;

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

$total_subtotal = array_sum($subtotals); // Sum of all item subtotals

$total_amount_with_shipping = $total_subtotal + $rate; // Include shipping in the total amount

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'hallyu0401@gmail.com'; 
    $mail->Password = 'peki ovkm vycz epkd'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('hallyu0401@gmail.com', 'Hallyu - Kpop Store'); 
    $mail->addAddress($user_email, $customerName);

    $mail->isHTML(true);
    $mail->Subject = "Order Confirmation for Order ID:{$orderinfo_id}";
    $mail->Body = "
    <h1>Thank you for your order, {$customerName}!</h1>
    <p>We appreciate your purchase. Here are your order details:</p>
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
    <p>Thank you for your purchase!</p>
    <p>Best regards,<br>Hallyu - Kpop Store</p>
";

    $mail->send();
    $_SESSION['message'] = "Order confirmation email sent successfully!";
    header("Location: view_order.php");
} catch (Exception $e) {
    error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    echo "Error: Unable to send email. Please contact support.";
}
?>