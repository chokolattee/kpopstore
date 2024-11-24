<?php
session_start();
include("../includes/config.php");
include("../includes/header.php");

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please log in to edit orders.';
    header("Location: /kpopstore/user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id' AND r.role_id = 1 LIMIT 1";
$result= mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['message'] = 'You must be logged in as admin to access this page.';
    header("Location: /kpopstore/user/login.php");
}

// Check if the orderinfo_id is passed via POST
if (isset($_POST['orderinfo_id'])) {
    $orderinfo_id = $_POST['orderinfo_id'];

    // Fetch the order details based on the orderinfo_id and user_id
    $sql = "SELECT orderinfo_id, full_name, date_placed, status, total_amount, date_shipped, date_received 
    FROM orderdetails 
    WHERE orderinfo_id = $orderinfo_id";
    $result = mysqli_query($conn, $sql);

    // If the order exists, display it for editing
    if (mysqli_num_rows($result) > 0) {
        $order = mysqli_fetch_assoc($result);

        echo "<div class='order-container'>";
        echo "<div class='d-flex justify-content-center row'>";
        echo "<div class='col-md-10'>";
        echo "<div class='table-responsive table-borderless'>";
        echo "<form action='update_order.php' method='POST'>";
        echo "<table class='table table-striped'>";
        echo "<thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Date Placed</th>
                    <th>Status</th>
                    <th>Final Total (With Shipping)</th>
                    <th>Date Shipped</th>
                    <th>Action</th>
                </tr>
              </thead>";
        echo "<tbody>";

        $orderId = $order['orderinfo_id'];
        $userName = $order['full_name'];
        $datePlaced = date('M d, Y', strtotime($order['date_placed']));
        $totalAmount = "₱" .$order['total_amount'];
        $status = $order['status'];
        $dateShipped = $order['date_shipped'] ? date('Y-m-d', strtotime($order['date_shipped'])) : '';

        // Status dropdown for editing
        $statusDropdown = "<select name='status' class='form-control'>
                            <option value='Pending'" . ($status == 'Pending' ? ' selected' : '') . ">Pending</option>
                            <option value='Shipped'" . ($status == 'Shipped' ? ' selected' : '') . ">Shipped</option>
                            <option value='Cancelled'" . ($status == 'Cancelled' ? ' selected' : '') . ">Cancelled</option>
                           </select>";

        echo "<tr>";
        echo "<td><input type='hidden' name='orderinfo_id' value='$orderId'>$orderId</td>";
        echo "<td>$userName</td>";
        echo "<td>$datePlaced</td>";
        echo "<td>$statusDropdown</td>";
        echo "<td>$totalAmount</td>";
        echo "<td><input type='date' name='date_shipped' class='form-control' value='$dateShipped'></td>";
        echo "<td><button type='submit' class='btn btn-primary btn-sm'>Update</button></td>";
        echo "</tr>";

        echo "</tbody>";
        echo "</table>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<p class='text-danger text-center'>Invalid Order ID or you don't have access to this order.</p>";
    }
} else {
    echo "<p class='text-danger text-center'>No order selected.</p>";
}

include("../includes/footer.php");
?>


<style>
.order-container {
    margin-top: 50px;
    margin-bottom: 50px;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
}

/* Table styling */
.table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 8px;
}

.table th,
.table td {
    padding: 12px 15px;
    text-align: center;
    font-size: 14px;
    border-bottom: 1px solid #ddd;
}

.table th {
    background-color: #4CAF50;
    color: white;
}

.table td {
    background-color: #ffffff;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f2f2f2;
}

/* Responsive table design */
.table-responsive {
    overflow-x: auto;
}

.table-body {
    font-family: Arial, sans-serif;
}

/* Style for 'No orders found' */
.text-center {
    font-size: 16px;
    color: #888;
    padding: 20px;
}

/* Extra styling for the order history items */
td {
    font-size: 14px;
}

/* Spacing adjustments */
.d-flex {
    display: flex;
    justify-content: center;
}

.row {
    margin-bottom: 30px;
}
</style>