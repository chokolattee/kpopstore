<?php
session_start();
include('./includes/header.php');
include('./includes/config.php');

$keyword = strtolower(trim($_GET['query']));

if (isset($_GET['query']) && !empty($keyword)) {
    $stmt = $conn->prepare("
        SELECT i.item_id AS itemId, i.item_name, i.description, 
               (SELECT img_path FROM itemimg WHERE item_id = i.item_id LIMIT 1) AS img_path, 
               i.sell_price 
        FROM item i
        INNER JOIN stock s ON i.item_id = s.item_id
        WHERE i.item_name LIKE ? 
        ORDER BY i.item_id DESC
    ");
    
    $searchTerm = "%" . $keyword . "%";  
    $stmt->bind_param("s", $searchTerm);  
    $stmt->execute();
    $results = $stmt->get_result();  
    echo '<div class="products" style="display: flex; flex-wrap: wrap;">'; 

    if ($results->num_rows > 0) {
        while ($row = $results->fetch_assoc()) {
            $itemId = $row['itemId'];
            $itemname = ($row['item_name']);
            $description = $row['description'];
            $imgPath = $row['img_path'];  
            $sellPrice = $row['sell_price'];
          
            echo '<div class="products-card">';
            echo "<img src='./item/$imgPath' alt='$description' />";
            echo "<h3>$itemname</h3>";
            echo "<p>₱$sellPrice</p>";
            echo '<form method="POST" action="cart_update.php">';
            echo "<input type='hidden' name='item_id' value='$itemId' />";
            echo '<input type="hidden" name="type" value="add" />';
            echo '<button type="submit" class="add_to_cart">Add to Cart</button>';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo '<p>No products found for your search.</p>';
    }

    $stmt->close();
}
?>
