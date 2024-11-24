<?php
session_start();
include('./includes/header.php');
include('./includes/config.php');


// SQL query to fetch only albums
$sql = "SELECT id.category,id.item_id, id.item_name, id.description, ii.img_path, id.sell_price, id.quantity 
        FROM item_details id
        INNER JOIN itemimg ii ON ii.item_id = id.item_id 
        INNER JOIN category c ON c.description=id.category
        WHERE id.category = 'Merchandise'
        GROUP BY id.item_id 
        ORDER BY id.item_id ASC";



// Execute the query
$results = mysqli_query($conn, $sql);


echo '<br><br> <div class="prod">
<h2>Merchandise</h2></div>';
echo '<section class="products" style="display: flex; flex-wrap: wrap;">';

// Loop through the results and generate album cards dynamically
while ($row = mysqli_fetch_assoc($results)) {
    $itemId = $row['item_id'];
    $itemname = $row['item_name'];
    $description = $row['description'];
    $imgPath = $row['img_path'];
    $sellPrice = $row['sell_price'];
    $quantity = $row['quantity'];

    echo '<div class="products-card">'; 
    echo "<img src='./item/$imgPath' alt='$itemname' />";
echo "<h3>$itemname</h3>";
echo "<p>Available: $quantity</p>";
echo "<p>₱$sellPrice</p>";

// Add to Cart Button - Only enable if user is logged in
if (isset($_SESSION['user_id'])) {
$user_id = $_SESSION['user_id'];
$sql_role_check = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id'
AND r.role_id = 2 LIMIT 1";
$result_role = mysqli_query($conn, $sql_role_check);

if (mysqli_num_rows($result_role) > 0) {
echo '
<div class="button-container">
    <form action="view_itemdetails.php" method="POST" style="display:inline;">
        <input type="hidden" name="item_id" value="' . $itemId . '">
        <button type="submit" class="item_details">View Details</button>
    </form>

    <form action="cart_update.php" method="POST" style="display:inline;">
        <input type="hidden" name="type" value="add">
        <input type="hidden" name="item_id" value="' . $itemId . '">
        <button type="submit" class="add_to_cart">Add to Cart</button>
    </form>
</div>';
}
} else {
echo '
<button type="button" class="item_details_disabled" onclick="window.location.href=\'/kpopstore/user/login.php\'">View
    Details</button>';

echo '
<button type="button" class="add_to_cart_disabled" onclick="window.location.href=\'/kpopstore/user/login.php\'">Add to
    Cart</button>';

$_SESSION['message'] = 'You must be logged in to access this.';
}

echo '</div>';
}

echo '</section>';
?>

<script src="scripts.js"></script>
</body>

<style>
/* Add your styles here */

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
    margin: 5px 0;
    font-family: 'Courier New', Courier, monospace;
}

/* Price Styling */
.products-card p {
    font-size: 14px;
    color: #333;
    margin: 10px 0 15px;
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

.button-container {
    display: flex;
    align-items: center;
    gap: 10px;
    /* Optional: adds spacing between buttons */
}
</style>

</html>