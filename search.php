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
            echo '
            <form action="view_itemdetails.php" method="POST" style="display:inline;">
                <input type="hidden" name="item_id" value="' . $itemId . '">
                <button type="submit" class="item_details">View Details</button>
            </form>';
    
            echo '
            <form action="cart_update.php" method="POST" style="display:inline;">
                <input type="hidden" name="type" value="add">
                <input type="hidden" name="item_id" value="' . $itemId . '">
                <button type="submit" class="add_to_cart">Add to Cart</button>
            </form>';
            echo '</div>';
        }
    } else {
        echo '<p>No products found for your search.</p>';
    }

    $stmt->close();
}
?>

<style>
body {
    background-color: #ddcae6;
    font-family: Arial, sans-serif;
    /* Default font for better readability */
}

/* Product Card Styling */
.products-card {
    background-color: #E5D4FF;
    border: 0.5px solid #E5CCFF;
    border-radius: 10px;
    margin: 10px;
    padding: 15px;
    text-align: center;
    width: 200px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    /* Align content vertically */
    justify-content: space-between;
    /* Ensure even spacing */
    transition: transform 0.3s, box-shadow 0.3s;
}

/* Product Cards Container */
.products {
    display: flex;
    justify-content: space-evenly;
    /* Distribute cards evenly */
    padding: 20px;
    gap: 20px;
    /* Adjust gap between cards */
    flex-wrap: wrap;
    /* Wrap cards on smaller screens */
    border-top: 2px solid #D4A5FF;
    border-bottom: 2px solid #D4A5FF;
    margin-bottom: 20px;
}

/* Section Title Styling */
.prod h2 {
    font-size: 45px;
    text-align: center;
    color: #5B148F;
}

/* Image Styling */
.products-card img {
    border-radius: 5px;
    width: 100%;
    /* Ensures image spans full width */
    height: auto;
    object-fit: cover;
    /* Ensures uniform appearance */
    margin-bottom: 10px;
    max-height: 150px;
    /* Limits image height */
}

/* Product Name Styling */
.products-card h3 {
    font-size: 18px;
    font-weight: bold;
    color: #5B148F;
    margin: 10px 0;
    font-family: 'Courier New', Courier, monospace;
}

/* Price Styling */
.products-card p {
    font-size: 14px;
    color: #333;
    margin: 5px 0 15px;
    font-family: cursive;
}

/* Buttons Container */
.edit-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

/* Buttons Styling */
.edit-buttons button,
.edit-buttons form button {
    padding: 8px 15px;
    font-size: 13px;
    font-family: sans-serif;
    background-color: #8c699a;
    color: #FFF;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.edit-buttons button:hover {
    background-color: #8666ba;
}



/* Intro Section */
.intro h2 {
    text-align: center;
    font-size: 40px;
    font-family: fantasy;
    color: #5B148F;
}

.intro h3 {
    text-align: center;
    font-family: monospace;
    font-size: 30px;
}

.intro p {
    text-align: center;
    font-size: 15px;
    font-family: 'Bookman Old Style', serif;
}

/* Navbar Styling */


/* Authentication Buttons */
.auth-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 0;
}

.auth-buttons button {
    padding: 8px 15px;
    font-size: 14px;
    background-color: #8c699a;
    color: #FFF;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.auth-buttons button:hover {
    background-color: #8666ba;
}

/* Button Margin Adjustment */
.edit-buttons button+button {
    margin-left: 10px;
}

/* Hover Effect for Product Card */
.products-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
}

form {
    margin: 0;
    /* Reset default margins for forms */
    padding: 0;
    display: inline-block;
    /* Ensure it doesn't disrupt button alignment */
}


.button-row {
    display: flex;
    align-items: center;
    gap: 10px;
    /* Optional: adds spacing between buttons */
}
</style>