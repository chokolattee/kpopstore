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
    $_SESSION['message'] = 'Please log in to access resources';
    header("Location: /kpopstore/user/login.php");
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id' AND r.role_id = 1 LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['message'] = 'You must be logged in as admin to access this page.';
    header("Location: /kpopstore/user/login.php");
    exit();
}

include("../includes/headera.php");

$sql = "SELECT DISTINCT orderinfo_id, full_name, date_placed, status, shipping_rate, date_shipped, date_received 
        FROM orderdetails ORDER BY orderinfo_id DESC";
$result = mysqli_query($conn, $sql);
include("../includes/alert.php");

echo "<div class='order-container'>";
echo "<h1>ORDER MANAGEMENT </h1>";
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
        $dateShipped = $order['date_shipped'];
        $date_shipped = $dateShipped ? date('M d, Y', strtotime($dateShipped)) : null; 
        $dateReceived = $order['date_received'];
        $date_received = $dateReceived ? date('M d, Y', strtotime($dateReceived)) : null; 
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
        echo "<td>";
        echo $date_shipped ? $date_shipped : 'Not Shipped';
        echo "</td>";
        echo "<td>";
        echo $date_received ? $date_received : 'Not Received';
        echo "</td>";

        echo "<td>";
        if ($orderStatus !== 'Cancelled' && !$date_shipped) {
            echo "<form action='edit_order.php' method='POST'>";
            echo "<input type='hidden' name='orderinfo_id' value='$orderId'>";
            echo "<button type='submit' class='btn btn-primary btn-sm'>Edit</button>";
            echo "</form>";
        } elseif ($date_shipped && !$date_received) {
            echo "<button class='btn btn-info btn-sm' disabled>Shipped</button>";
        } elseif ($date_shipped && $date_received) {
            echo "<button class='btn btn-success btn-sm' disabled>Received</button>";
        } else {
            echo "<button class='btn btn-secondary btn-sm' disabled>Cancelled</button>";
        }
        echo "</td>";
        
        echo "</td>";
        
        echo "</tr>";
        
        echo "</td>";

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
        background-color: #EEDEF0;
        padding: 20px;
        border-radius: 8px;
    }

    .order-container h1 {
        text-align: center;
        font-size: 40px;
    }

    /* Table styling */
    .table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
    }

    .table th,
    .table td {
        padding: 12px 15px;
        text-align: center;
        font-size: 14px;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #dab1da;
        color: white;
    }

    .table td {
        background-color: #ffffff;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f2f2f2;
    }

    /* Ensure the table fits within the container */
    .table-responsive {
        width: 100%;
        display: block;
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