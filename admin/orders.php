<?php
session_start();
include("../includes/config.php");


// CREATE OR REPLACE VIEW orderdetails AS
// SELECT 
//     o.orderinfo_id,
//     u.user_id,
//     CONCAT(u.fname, ' ', u.lname) AS full_name,
//     u.address,
//     u.contact_number,
//     GROUP_CONCAT(i.item_id ORDER BY i.item_id SEPARATOR ', ') AS item_ids,
//     GROUP_CONCAT(i.item_name ORDER BY i.item_id SEPARATOR ', ') AS items,
//     GROUP_CONCAT(CAST(i.sell_price AS DECIMAL(10,2)) ORDER BY i.item_id SEPARATOR ', ') AS sell_prices, 
//     GROUP_CONCAT(ol.quantity ORDER BY i.item_id SEPARATOR ', ') AS quantities,
//     GROUP_CONCAT(CAST(ol.quantity * i.sell_price AS DECIMAL(10,2)) ORDER BY i.item_id SEPARATOR ', ') AS subtotals,  
//     sh.region AS shipping_region,
//     sh.rate AS shipping_rate,
//     o.date_placed,
//     o.date_shipped,
//     o.date_received,
//     s.status,
//     FORMAT(SUM((ol.quantity * i.sell_price) + sh.rate), 2) AS total_amount  
// FROM 
//     orderinfo o
// JOIN 
//     user u ON o.user_id = u.user_id
// JOIN 
//     orderline ol ON o.orderinfo_id = ol.orderinfo_id
// JOIN 
//     item i ON ol.item_id = i.item_id
// JOIN 
//     shipping sh ON o.shipping_id = sh.shipping_id
// JOIN 
//     status s ON o.status_id = s.status_id
// GROUP BY 
//     o.orderinfo_id, 
//     u.user_id, 
//     full_name, 
//     u.address, 
//     u.contact_number, 
//     sh.region, 
//     sh.rate, 
//     o.date_placed, 
//     o.date_shipped, 
//     o.date_received, 
//     s.status;


if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please log in to view your orders.';
    header("Location: /kpopstore/user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT DISTINCT orderinfo_id, full_name, date_placed, status, shipping_rate, date_shipped, date_received 
        FROM orderdetails";
$result = mysqli_query($conn, $sql);

echo "<div class='order-container'>";
echo "<div class='d-flex justify-content-center row'>";
echo "<div class='col-md-10'>";
echo "<div class='table-responsive table-borderless'>";
echo "<table class='table table-striped'>";
echo "<thead>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Items</th>
            <th>Quantity</th>
            <th>Sell Price</th>
            <th>Date Placed</th>
            <th>Status</th>
            <th>Final Total (with Shipping)</th>
            <th>Date Shipped</th>
            <th>Date Received</th>
            <th>Action</th>
        </tr>
      </thead>";
echo "<tbody>";

if (mysqli_num_rows($result) > 0) {
    while ($order = mysqli_fetch_assoc($result)) {
        $orderId = $order['orderinfo_id'];
        $userName = $order['full_name'];
        $orderStatus = $order['status'];
        $shippingRate = $order['shipping_rate'];
        $dateShipped = $order['date_shipped'] ? date('M d, Y', strtotime($order['date_shipped'])) : 'Not Shipped';
        $dateReceived = $order['date_received'] ? date('M d, Y', strtotime($order['date_received'])) : 'Not Received';
        $datePlaced = date('M d, Y', strtotime($order['date_placed']));

        $sql_items = "SELECT items, quantities, sell_prices, total_amount 
                      FROM orderdetails 
                      WHERE orderinfo_id = $orderId";
        $items_result = mysqli_query($conn, $sql_items);

        $items = [];
        $quantities = [];
        $sellPrices = [];
        $finalTotal = 0;

        while ($item = mysqli_fetch_assoc($items_result)) {
            $items[] = $item['items'];
            $quantities[] = $item['quantities'];
            $sellPrices[] = "₱" . $item['sell_prices'];
            $finalTotal = $item['total_amount']; 
        }

        $itemsList = implode("<br>", $items);
        $quantitiesList = implode("<br>", $quantities);
        $sellPricesList = implode("<br>", $sellPrices);

        echo "<tr>";
        echo "<td>$orderId</td>"; 
        echo "<td>$userName</td>"; 
        echo "<td>$itemsList</td>"; 
        echo "<td>$quantitiesList</td>"; 
        echo "<td>$sellPricesList</td>";
        echo "<td>$datePlaced</td>";  
        echo "<td>$orderStatus</td>"; 
        echo "<td>₱" . $finalTotal . "</td>";  
        echo "<td>$dateShipped</td>"; 
        echo "<td>$dateReceived</td>"; 

            echo "<td>
                  <form action='edit_order.php' method='POST'>
    <input type='hidden' name='orderinfo_id' value='$orderId'>
    <button type='submit' class='btn btn-primary btn-sm'>Edit</button>
</form>
                  </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='11' class='text-center'>No orders found</td></tr>";
}

echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";

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