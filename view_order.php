<?php
session_start();
include('./includes/header.php');
include('./includes/config.php');

include('./includes/alert.php');

echo '<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">';

if (!isset($_SESSION['user_id'])) {
  $_SESSION['message'] = 'Please log in to view your orders.';
  header("Location: /kpopstore/user/login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT DISTINCT orderinfo_id, date_placed, status 
        FROM orderdetails 
        WHERE user_id = $user_id";

$result = mysqli_query($conn, $sql);




echo "<div class='order-container'>";
echo "<div class='d-flex flex-wrap justify-content-center'>";

if (mysqli_num_rows($result) > 0) {
  while ($order = mysqli_fetch_assoc($result)) {
    $orderId =  "Order ID " . $order['orderinfo_id'];

    $datePlaced = date('F d, Y', strtotime($order['date_placed']));
    $statusLabel = $order['status'];

    echo "<div class='order-card'>";
    echo "<h5>$orderId</h5>";
    echo "<p class='order-date'>Placed on $datePlaced <span class='status-badge'>$statusLabel</span></p>";

    $sql_items = "SELECT item_ids, items, quantities, sell_prices 
                      FROM orderdetails 
                      WHERE orderinfo_id = '{$order['orderinfo_id']}'";
    $items_result = mysqli_query($conn, $sql_items);

    while ($item = mysqli_fetch_assoc($items_result)) {
      $itemId = $item['item_ids'];
      $itemName = $item['items'];
      $itemPrice = "₱" . $item['sell_prices'];
      $quantity = $item['quantities'];

      $sql_image = "SELECT img_path 
                          FROM itemimg 
                          WHERE item_id = '$itemId' 
                          LIMIT 1";
      $image_result = mysqli_query($conn, $sql_image);
      $image = mysqli_fetch_assoc($image_result);
      $itemImage = "/kpopstore/item/" . $image['img_path'];

      echo "<div class='item-row'>";
      echo "<img src='$itemImage' alt='$itemName' class='item-image'>";
      echo "<div class='item-details'>";
      echo "<h6>$itemName</h6>";
      echo "<p>Price: $itemPrice</p>";
      echo "<p>Quantity: $quantity</p>";
      echo "</div>";
      echo "</div>";
    }

    echo "<div class='order-actions'>";
    if ($statusLabel == "Pending") {
      echo "<form action='cancel_order.php' method='POST'>";
      echo "<input type='hidden' name='orderinfo_id' value='{$order['orderinfo_id']}'>";
      echo "<button type='submit' class='btn btn-warning btn-sm'>Cancel Order</button>";
      echo "</form>";
    } elseif ($statusLabel == "Shipped") {
      echo "<form action='receive_order.php' method='POST'>";
      echo "<input type='hidden' name='orderinfo_id' value='{$order['orderinfo_id']}'>";

      echo "<div class='form-group'>";
      echo "<label for='date_received'>Date Received:</label>";
      echo "<input type='date' name='date_received' class='form-control'>";
      echo "</div>";

      echo "<button type='submit' class='btn btn-success btn-sm'>Received</button>";
      echo "</form>";
    } elseif ($statusLabel == "Delivered") {
      $orderinfo_id = $order['orderinfo_id'];

      $sql_review = "SELECT review_id FROM review WHERE orderinfo_id = $orderinfo_id AND user_id = $user_id";
      $review_result = mysqli_query($conn, $sql_review);

      if ($review_result && mysqli_num_rows($review_result) > 0) {
        echo "<form action='/kpopstore/review/index.php' method='GET' style='display:inline;'>";
        echo "<input type='hidden' name='orderinfo_id' value='{$order['orderinfo_id']}'>";
        echo "<button type='submit' class='btn btn-primary btn-sm'>Review Added</button>";
        echo "</form>";
      } else {
        echo "<input type='hidden' name='orderinfo_id' value='{$order['orderinfo_id']}'>";
        echo "<button type='button' class='btn btn-success btn-sm' onclick='openReviewModal({$order['orderinfo_id']})'>Add Review</button>";
      }
    } elseif ($statusLabel == "Cancelled") {
      echo "<button class='btn btn-danger btn-sm' disabled>Cancelled</button>";
    }
    echo "</div>";

    echo "</div>";
  }
} else {
  echo "<div class='no-orders'>No orders found</div>";
}


echo "</div>";
echo "</div>";

include('./includes/footer.php');
?>

<div id="reviewModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Add a Review</h3>
        <form id="reviewForm" action="/kpopstore/review/store.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="orderinfo_id" id="orderinfoId" value="">
            <div class="stars" id="starRating">
                <span data-value="1">&#9733;</span>
                <span data-value="2">&#9733;</span>
                <span data-value="3">&#9733;</span>
                <span data-value="4">&#9733;</span>
                <span data-value="5">&#9733;</span>
                <input type="hidden" name="rating" id="ratingValue">
            </div>
            <textarea name="review" placeholder="What is your view?"></textarea>
            <div class="upload-section">
                <label for="imageUpload">Upload Images</label>
                <input class="form-control" type="file" name="img_path[]" multiple /><br />

                <small><?php
                if (isset($_SESSION['imageError'])) {
                  echo $_SESSION['imageError'];
                  unset($_SESSION['imageError']);
                }
                ?></small>
                <div id="imagePreview" class="image-preview"></div>
            </div>
            <div class="buttons">
                <button type="button" class="cancel" onclick="closeReviewModal()">Cancel</button>
                <button type="submit" class="save" name="submit" value="submit">Submit</button>
            </div>
        </form>
    </div>
</div>


<style>
/* Main container for the order history */
body {
    background-color: #1e1e2f;
    color: #fff;
    font-family: Arial, sans-serif;
}

.order-container {
    padding: 20px;
}

.d-flex {
    display: flex;
}

.flex-wrap {
    flex-wrap: wrap;
}

.order-card {
    background-color: #2b2b3c;
    border-radius: 10px;
    width: 100%;
    max-width: 550px;
    margin: 10px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.order-card h5 {
    color: #fff;
    margin-bottom: 10px;
}

.order-date {
    font-size: 0.9em;
    color: #bdbdbd;
}

.status-badge {
    background-color: #28a745;
    color: #fff;
    padding: 3px 8px;
    border-radius: 5px;
    font-size: 0.8em;
}

.item-row {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #3c3c4d;
    padding-bottom: 10px;
    margin-bottom: 10px;
}

.item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    margin-right: 15px;
    border-radius: 5px;
}

.item-details h6 {
    color: #fff;
    margin: 0 0 5px 0;
}

.item-details p {
    margin: 0;
    font-size: 0.9em;
    color: #bdbdbd;
}

.order-actions {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
}

.order-actions button {
    width: 100%;
}

.no-orders {
    text-align: center;
    color: #bdbdbd;
    margin-top: 20px;
}

/* Modal Styles */
.modal {
    display: none;
    /* Hidden by default */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    /* Black background with transparency */
}

.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    width: 400px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 18px;
    cursor: pointer;
    color: #888;
}

.close-btn:hover {
    color: #000;
}

textarea {
    width: 100%;
    height: 60px;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 8px;
    font-size: 14px;
    resize: none;
}

.stars span {
    font-size: 24px;
    color: #ccc;
    /* Gray color for unselected stars */
    cursor: pointer;
    /* Pointer cursor for hover effect */
    transition: color 0.2s ease-in-out;
    /* Smooth color transition */
}

/* Hover effect: highlight stars from left to right */
.stars span:hover,
.stars span:hover~span {
    color: #ffcc00;
    /* Yellow color for hover */
}

/* Highlight the selected stars */
.stars span.selected {
    color: #ffcc00;
    /* Yellow color for selected stars */
}

.upload-section {
    margin: 10px 0;
}

.upload-section input[type="file"] {
    margin-top: 5px;
}

.image-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.image-preview img {
    width: 70px;
    height: 70px;
    border-radius: 8px;
    object-fit: cover;
}

.modal-content h3 {
    color: black;
    /* Change the heading color to black */
    font-size: 18px;
    /* Optional: Adjust font size if needed */
    font-weight: bold;
    /* Optional: Make the text bold */
}

/* Rating Text */
.stars label {
    color: black;
    /* Change the rating label text to black */
    font-size: 16px;
    /* Optional: Adjust font size */
    font-weight: bold;
    /* Optional: Make the text bold */
}
</style>

<script>
// Open and close modal
function openReviewModal(orderId) {
    document.getElementById("reviewModal").style.display = "block";
    document.getElementById("orderinfoId").value = orderId; // Pass the order ID to the modal form
}

function closeReviewModal() {
    document.getElementById("reviewModal").style.display = "none";
}

// Preview uploaded images
const imageUpload = document.querySelector('input[type="file"]');
const imagePreview = document.getElementById("imagePreview");

imageUpload.addEventListener("change", function() {
    imagePreview.innerHTML = ""; // Clear previous previews
    const files = imageUpload.files;

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function(e) {
            const img = document.createElement("img");
            img.src = e.target.result;
            img.style.width = "80px"; // Optional: Set preview image width
            img.style.height = "80px"; // Optional: Set preview image height
            img.style.margin = "5px"; // Optional: Add spacing between images
            imagePreview.appendChild(img);
        };

        reader.readAsDataURL(file);
    }
});

// Star rating functionality
const stars = document.querySelectorAll("#starRating span");
const ratingInput = document.getElementById("ratingValue");

// Add hover and click functionality to each star
stars.forEach((star, index) => {
    // Hover effect
    star.addEventListener("mouseover", () => {
        resetStars(); // Reset all stars
        highlightStars(index); // Highlight stars up to the hovered one
    });

    // Click to select rating
    star.addEventListener("click", () => {
        ratingInput.value = index + 1; // Set the rating value (1-based index)
        resetStars();
        highlightStars(index, true); // Highlight stars as selected
    });

    // Remove hover effect when the mouse leaves
    star.parentNode.addEventListener("mouseleave", () => {
        resetStars();
        if (ratingInput.value) {
            highlightStars(ratingInput.value - 1, true); // Reapply the selected stars
        }
    });
});

// Function to reset all stars
function resetStars() {
    stars.forEach((star) => {
        star.classList.remove("selected");
        star.style.color = "#ccc"; // Reset color
    });
}

// Function to highlight stars up to a given index
function highlightStars(index, isSelected = false) {
    stars.forEach((star, i) => {
        if (i <= index) {
            if (isSelected) {
                star.classList.add("selected");
            }
            star.style.color = "#ffcc00"; // Highlight stars
        }
    });
}
</script>