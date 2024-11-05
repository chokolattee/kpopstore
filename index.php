<?php 
session_start();
include('./includes/header.php');
include('./includes/config.php');

// if (isset($_SESSION["cart_products"]) && count($_SESSION["cart_products"]) > 0) {
//     echo '<div class="cart-view-table-front" id="view-cart">';
//     echo '<h3>Your Shopping Cart</h3>';
//     echo '<form method="POST" action="cart_update.php">';
//     echo '<table width="100%" cellpadding="6" cellspacing="0"><tbody>';
//     $total = 0;
//     foreach ($_SESSION["cart_products"] as $index => $cart_itm) {
//         $product_code = htmlspecialchars($cart_itm["item_id"]);
//         $product_name = htmlspecialchars($cart_itm["item_name"]);
//         $product_qty = (int)$cart_itm["item_qty"];
//         $product_price = (float)$cart_itm["item_price"];
//         $subtotal = $product_price * $product_qty;
//         $total += $subtotal;

//         echo '<tr class="' . ($index % 2 ? 'odd' : 'even') . '">';
//         echo "<td>Qty <input type='number' size='2' maxlength='2' min='1' name='product_qty[$product_code]' value='$product_qty' /></td>";
//         echo "<td>$product_name</td>";
//         echo '<td><input type="checkbox" name="remove_code[]" value="' . $product_code . '" /> Remove</td>';
//         echo '</tr>';
//     }
//     echo '<tr><td colspan="2">Total: $' . number_format($total, 2) . '</td>';
//     echo '<td><button type="submit">Update</button><a href="view_cart.php" class="button">Checkout</a></td></tr>';
//     echo '</tbody></table></form></div>';
// }


$sql = "SELECT i.item_id AS itemId, i.description, ii.img_path,i.sell_price FROM item i INNER JOIN stock s ON i.item_id = s.item_id LEFT JOIN itemimg ii ON i.item_id = ii.item_id 
    GROUP BY i.item_id ORDER BY i.item_id ASC";
$results = mysqli_query($conn, $sql);
echo '<div class="products cat1"><h2>All Products</h2></div>'; 
echo '<div class="products" style="display: flex; flex-wrap: wrap;">'; 

while ($row = mysqli_fetch_assoc($results)) {
    $itemId = ($row['itemId']);
    $description = ($row['description']);
    $imgPath = ($row['img_path']);
    $sellPrice = ($row['sell_price']);
    
    echo '<div class="products-card">';
    echo "<img src='./item/$imgPath' alt='$description' />";
    echo "<h3>$description</h3>";
    echo "<p>₱$sellPrice</p>";
    echo '<form method="POST" action="cart_update.php">';
    echo "<input type='hidden' name='item_id' value='$itemId' />";
    echo '<input type="hidden" name="type" value="add" />';
    echo '<button type="submit" class="add_to_cart">Add to Cart</button>';
    echo '</form></div>';
}

echo '</div>'; 

// $item_id = (int)$_GET['id'];

// $sql_detail = "
//     SELECT 
//         i.description, 
//         ii.img_path, 
//         i.sell_price 
//     FROM 
//         item i 
//     LEFT JOIN 
//         itemimg ii ON i.item_id = ii.item_id 
//     WHERE 
//         i.item_id = $item_id";

// $result_detail = mysqli_query($conn, $sql_detail);
// $row_detail = mysqli_fetch_assoc($result_detail);

// if ($row_detail) {
//     echo "<h2>{$row_detail['description']}</h2>";
//     echo "<p>Price: ₱{$row_detail['sell_price']}</p>";
    
//     // Fetch all images for the selected item
//     $sql_images = "SELECT img_path FROM itemimg WHERE item_id = $item_id";
//     $images_result = mysqli_query($conn, $sql_images);
    
//     while ($image_row = mysqli_fetch_assoc($images_result)) {
//         echo "<img src='./item/{$image_row['img_path']}' alt='{$row_detail['description']}' width='250' height='250' />";
//     }
// } else {
//     echo "Item not found.";
// }
?>
