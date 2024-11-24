<?php
session_start();
include('./includes/config.php');
include('./includes/header.php');

// SQL query to get item details
$sql = "SELECT id.item_id, id.item_name, id.description, ii.img_path, id.sell_price, id.quantity FROM
                item_details id
                LEFT JOIN itemimg ii ON ii.item_id = id.item_id
                GROUP BY id.item_id
                ORDER BY id.item_id ASC";

// Ensure user is logged in and has the correct role
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please log in to access resources';
    header("Location: /kpopstore/user/login.php");
}

$user_id = $_SESSION['user_id'];
$sql_role_check = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id' AND r.role_id = 2 LIMIT 1";
$result_role = mysqli_query($conn, $sql_role_check);

if (mysqli_num_rows($result_role) == 0) {
    $_SESSION['message'] = 'You must be logged in as a user to access this page.';
    header("Location: /kpopstore/user/login.php");
}

// Check for selected item
if (isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    $sql = "SELECT * FROM item_details WHERE item_id = $item_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $itemName = $row['item_name'];
        $sellPrice = $row['sell_price'];
        $description = $row['description'];

        $images = explode(',', $row['images']);
    } else {
        echo "<p>Item not found.</p>";
        exit();
    }

    $reviews_query = "SELECT review_id, orderinfo_id, user_id, rate, comment, full_name, img_paths
    FROM reviewdetails WHERE FIND_IN_SET('$item_id', item_ids)";
    $reviews_result = mysqli_query($conn, $reviews_query);
    $reviews = [];

    if ($reviews_result) {
        while ($row = mysqli_fetch_assoc($reviews_result)) {
            $reviews[] = $row;
        }
    }
} else {
    echo "<p>No item selected.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITEM DETAILS</title>
    <link rel="stylesheet" href='/kpopstore/includes/style/style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <main>
        <!-- Item Details Section -->
        <div class="details-wrapper">
            <div class="details-container">
                <div class="slider-container">
                    <div class="slides" id="slides">
                        <?php
                        // Display item images
                        foreach ($images as $image) {
                            $imagePath = "/kpopstore/item/" . trim($image);
                            echo "<div class='slide'><img src='$imagePath' alt='Item Image'></div>";
                        }
                        ?>
                    </div>
                    <button class="prev" onclick="moveSlide(-1)">❮</button>
                    <button class="next" onclick="moveSlide(1)">❯</button>
                </div>
            </div>
            <br>
            <div class="details-info">
                <h3 style="text-align:center; font-size: 25px;">Item Information</h3>
                <br>
                <p><strong>Name:</strong> <?php echo ($itemName); ?></p>
                <br>
                <p><strong>Price:</strong> ₱<?php echo $sellPrice; ?></p>
                <br>
                <p><strong>Description:</strong> <?php echo nl2br(($description)); ?></p>

                <!-- Add to Cart Form -->
                <form action="cart_update.php" method="POST"
                    style="display: flex; justify-content: center; align-items: center; width: 100%; margin-top: 20px;">
                    <input type="hidden" name="type" value="add">
                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>"> <!-- Correct item_id -->
                    <br><button type="submit" class="add_to_cart"
                        style="width: 100%; max-width: 400px; padding: 10px; font-size: 16px;">Add to Cart</button>
                </form>

            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-container">
            <h3 style="text-align:center; font-size:25px;">Customer Reviews</h3>
            <br>
            <?php if (count($reviews) > 0): ?>
            <div class="review-list">
                <?php
                foreach ($reviews as $review):
                    $reviewImages = explode(',', $review['img_paths']);
                    ?>
                <div class="review-item mb-3 p-3 border rounded">
                    <h5><?php echo ($review['full_name']); ?></h5>

                    <div class="review-rating">
                        <?php
                            $rating = $review['rate'];
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? '<span class="star">&#9733;</span>' : '<span class="star">&#9734;</span>';
                            }
                        ?>
                        (Rating: <?php echo $rating; ?>/5)
                    </div>

                    <p><?php echo nl2br(($review['comment'])); ?></p>

                    <!-- Display Review Images -->
                    <?php if (!empty($reviewImages)): ?>
                    <div class="review-images">
                        <div class="image-gallery">
                            <?php
                                foreach ($reviewImages as $image):
                                    echo "<div class='image-item'>";
                                    echo "<img src='/kpopstore/review/" . trim($image) . "' alt='Review Image' class='review-image'>";
                                    echo "</div>";
                                endforeach;
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p>No reviews yet for this item.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>

<script>
// Slider functionality
let currentSlide = 0;

function showSlide(index) {
    const slides = document.getElementById("slides");
    const totalSlides = slides.children.length;
    currentSlide = (index + totalSlides) % totalSlides; // Handle circular navigation
    const offset = -currentSlide * 100; // Move by 100% of the slider width
    slides.style.transform = `translateX(${offset}%)`;
}

function moveSlide(step) {
    showSlide(currentSlide + step);
}

showSlide(currentSlide);


function moveSlide(step) {
    showSlide(currentSlide + step);
}

// Initialize
showSlide(currentSlide);



function moveSlide(step) {
    showSlide(currentSlide + step);
}

showSlide(currentSlide);
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Add event listener to all "Update Cart" buttons
    const buttons = document.querySelectorAll(".update-cart-btn");

    buttons.forEach(button => {
        button.addEventListener("click", function() {
            const itemId = this.getAttribute("data-item-id");
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "/kpopstore/cart_update.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Send data to the server
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Handle response
                    alert(xhr
                        .responseText); // Optional: replace with better UI feedback
                }
            };

            xhr.send("item_id=" + encodeURIComponent(itemId) + "&action=update");
        });
    });
});
</script>
</body>

</html>


<style>
/* Details container */
* {
    box-sizing: border-box;
}

.details-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    /* Ensures alignment starts at the top */
    gap: 30px;
    /* Space between the sections */
    margin: 20px auto;
    /* Centers the entire content */
    width: 80%;
    /* Adjust as necessary */
}

/* Details container positioned on the left */
.details-container {
    flex: 3;
    /* Takes more space */
    padding: 15px;
    background-color: #D4A5FF;
    border-radius: 20px;
    box-shadow: 0 8px 8px rgba(0, 0, 0, 0.1);
}

/* Details info positioned on the right */
.details-info {
    flex: 2;
    /* Takes less space */
    padding: 20px;
    background-color: #D4A5FF;
    border-radius: 20px;
    box-shadow: 0 8px 8px rgba(0, 0, 0, 0.1);
    text-align: justify;
    margin-top: 20px;
}


/* Slider container */
/* Slider container */
.slider-container {
    position: relative;
    width: 100%;
    max-width: 600px;
    /* Adjust based on your layout */
    height: 300px;
    /* Fixed height for uniformity */
    overflow: hidden;
    margin: 0 auto;
    /* Center the container horizontally */
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    /* Center images horizontally */
    align-items: center;
    /* Center images vertically */
}

/* Slide structure */
.slides {
    display: flex;
    transition: transform 0.5s ease-in-out;
    height: 100%;
    margin: 0;
    padding: 0;
    width: 100%;
}

/* Each individual slide */
.slide {
    flex: 0 0 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
    padding: 0;

}

/* Image inside the slide */
.slides img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    display: block;
    /* Ensures the image fills the container without distortion */
    border-radius: 10px;
    /* Optional: Add a subtle rounded border */
}


/* Navigation buttons for the slider */
.prev,
.next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 2rem;
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
}

.prev {
    left: 10px;
}

.next {
    right: 10px;
}

/* Gallery container */
.image-gallery {
    display: flex;
    flex-wrap: wrap;
    /* Allows multiple rows if images exceed one line */
    gap: 10px;
    /* Adds spacing between images */
    margin-top: 10px;
}

/* Individual image item */
.image-item {
    width: 80px;
    /* Adjust as needed */
    height: 80px;
    /* Adjust as needed */
    border: 2px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
    /* Ensures no overflow */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    /* Subtle shadow effect */
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fff;
    /* Background color for image container */
}

/* Image inside the gallery */
.review-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    /* Maintains aspect ratio and fills container */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    /* Smooth hover effect */
}

/* Hover effect on images */
.image-item:hover .review-image {
    transform: scale(1.1);
    /* Slight zoom on hover */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    /* Enhanced shadow on hover */
}


/* Review item styling */
.review-item {
    background-color: #E3C4E3;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Reviews container */
.reviews-container {
    width: 100%;
    background-color: #ecd8ec;
    border-radius: 5px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Stars rating */
.review-rating .star {
    color: gold;
    font-size: 1.5rem;
}
</style>