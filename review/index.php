<?php
session_start();
include("../includes/config.php");
include("../includes/header.php");

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please log in to view your reviews.';
    header("Location: /kpopstore/user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql_reviews = "SELECT review_id, orderinfo_id, comment, rate, img_paths
                FROM reviewdetails WHERE user_id = '$user_id'";

$result_reviews = mysqli_query($conn, $sql_reviews);

$reviews = []; 
while ($review = mysqli_fetch_assoc($result_reviews)) {
    $review['images'] = $review['img_paths'] ? explode(',', $review['img_paths']) : [];
    $reviews[] = $review; 
}

echo "<div class='reviews-container'>";
echo "<h3>Your Reviews</h3>";

if (count($reviews) > 0) {
    foreach ($reviews as $review) {
        $reviewId = $review['review_id'];
        $orderId = $review['orderinfo_id'];
        $comment = htmlspecialchars($review['comment']);
        $rate = $review['rate'];
        $images = $review['images'];

        echo "<div class='review-item mb-3 p-3 border rounded'>";
        echo "<p>Order ID: $orderId</p>";
        echo "<div class='review-rating'>";

        // Render rating stars
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rate) {
                echo '<span class="star">&#9733;</span>';
            } else {
                echo '<span class="star">&#9734;</span>';
            }
        }
        echo " ($rate/5)</div>";

        echo "<p>$comment</p>";

        if (!empty($images)) {
            echo "<div class='review-images'>";
            echo "<div class='image-gallery'>";
            foreach ($images as $imgPath) {
                echo "<div class='image-item'>";
                echo "<img src='/kpopstore/review/$imgPath' alt='Review Image' class='review-image'>";
                echo "</div>";
            }
            echo "</div>";
            echo "</div>";
        }

        // Actions (Edit/Delete)
        echo "<div class='review-actions'>";
        echo "<button type='button' class='btn btn-primary btn-sm' 
                onclick='openReviewModal({$reviewId}, \"" . addslashes($comment) . "\", {$rate})'>Edit</button>";
        echo "<form action='delete.php' method='POST' style='display:inline;'>";
        echo "<input type='hidden' name='review_id' value='{$reviewId}'>";
        echo "<button type='submit' class='btn btn-danger btn-sm' 
                onclick='return confirm(\"Are you sure you want to delete this review?\");'>Delete</button>";
        echo "</form>";
        echo "</div>";

        echo "</div>"; 
    }
} else {
    echo "<p>No reviews found.</p>";
}

echo "</div>";
include("../includes/footer.php");
?>


<div id="reviewModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeReviewModal()">&times;</span>
        <h3>Edit Review</h3>
        <form id="reviewForm" action="/kpopstore/review/update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="review_id" id="reviewId" value="">
            <div class="stars" id="starRating">
                <span data-value="1">&#9733;</span>
                <span data-value="2">&#9733;</span>
                <span data-value="3">&#9733;</span>
                <span data-value="4">&#9733;</span>
                <span data-value="5">&#9733;</span>
                <input type="hidden" name="rating" id="ratingValue">
            </div>
            <textarea name="review" id="reviewText" placeholder="What is your view?"></textarea>
            <div class="upload-section">
                <label for="imageUpload">Upload Images</label>
                <input class="form-control" type="file" name="img_path[]" multiple /><br />
                <small><?php
            if (isset($_SESSION['imageError'])) {
                echo $_SESSION['imageError'];
                unset($_SESSION['imageError']);
            }
        ?></small>
            </div>
            <div class="buttons">
                <button type="button" class="cancel" onclick="closeReviewModal()">Cancel</button>
                <button type="submit" class="save" name="submit" value="submit">Submit</button>
            </div>
        </form>
    </div>
</div>


<style>
.reviews-container {
    margin-top: 50px;
    margin-bottom: 50px;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
}

.review-list {
    margin-top: 20px;
}

.review-item {
    background-color: #fff;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.review-rating {
    color: #ffb400;
}

.star {
    font-size: 18px;
}

.review-images img {
    max-width: 100%;
    height: 300px;
    border-radius: 5px;
}

.review-actions {
    margin-top: 10px;
}

.review-actions button {
    margin-right: 5px;
}

.btn-sm {
    padding: 6px 12px;
}
</style>

<style>
.reviews-container {
    margin: 30px auto;
    /* Center and add space */
    padding: 20px;
    max-width: 900px;
    /* Limit the width for better readability */
    background-color: #fdfdfd;
    /* Light background for better contrast */
    border: 1px solid #ddd;
    /* Subtle border */
    border-radius: 10px;
    /* Smooth corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    /* Soft shadow for elevation */
}

.reviews-container h3 {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    /* Darker text color for better visibility */
    margin-bottom: 20px;
    text-align: center;
    /* Center the header */
}

.review-list {
    margin-top: 10px;
    list-style: none;
    /* Remove default list styling */
    padding: 0;
}

.review-item {
    background-color: #fff;
    /* White background for each item */
    border: 1px solid #eee;
    /* Light border for separation */
    border-radius: 8px;
    /* Rounded corners */
    padding: 15px;
    margin-bottom: 20px;
    /* Space between items */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    /* Subtle shadow effect */
}

.review-rating {
    color: #ffb400;
    /* Gold stars for ratings */
    font-size: 16px;
    margin-bottom: 10px;
    /* Space below the stars */
}

.star {
    font-size: 20px;
    margin-right: 2px;
    /* Space between stars */
}

.review-images {
    margin-top: 15px;
    /* Space above images */
}

.image-gallery {
    display: flex;
    flex-wrap: wrap;
    /* Allow images to wrap if they exceed container width */
    gap: 10px;
    /* Space between images */
    margin-top: 10px;
}

.image-item {
    width: 100px;
    /* Thumbnail size */
    height: 100px;
    overflow: hidden;
    /* Clip overflowing parts */
    border: 2px solid #eee;
    /* Subtle border */
    border-radius: 8px;
    /* Rounded corners */
    background-color: #f9f9f9;
    /* Neutral background */
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    /* Light shadow */
}

.review-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    /* Maintain aspect ratio */
    transition: transform 0.3s, box-shadow 0.3s;
    /* Smooth hover effect */
}

.image-item:hover .review-image {
    transform: scale(1.1);
    /* Slight zoom on hover */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    /* Enhanced shadow on hover */
}

.review-actions {
    margin-top: 15px;
    /* Space above actions */
    display: flex;
    gap: 10px;
    /* Space between buttons */
}

.review-actions button {
    padding: 8px 12px;
    /* Button padding */
    font-size: 14px;
    border-radius: 5px;
    /* Rounded corners */
    border: none;
    /* Remove default border */
    cursor: pointer;
    transition: background-color 0.3s ease;
    /* Smooth color change */
}

.review-actions .btn-primary {
    background-color: #007bff;
    /* Primary blue */
    color: #fff;
    /* White text */
}

.review-actions .btn-primary:hover {
    background-color: #0056b3;
    /* Darker blue on hover */
}

.review-actions .btn-danger {
    background-color: #dc3545;
    /* Danger red */
    color: #fff;
    /* White text */
}

.review-actions .btn-danger:hover {
    background-color: #a71d2a;
    /* Darker red on hover */
}


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

.stars span.selected {
    color: #ffcc00;
    /* Yellow for selected stars */
}

.stars span:hover,
.stars span:hover~span {
    color: #ffcc00;
    /* Highlight stars on hover */
}

.stars span {
    font-size: 24px;
    color: #ccc;
    /* Default gray for unselected stars */
    cursor: pointer;
    /* Show pointer cursor */
    transition: color 0.3s ease;
    /* Smooth color change */
}

.upload-section {
    margin: 10px 0;
}

.upload-section input[type="file"] {
    margin-top: 5px;
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
function openReviewModal(reviewId, comment, rating) {
    // Open the modal
    document.getElementById("reviewModal").style.display = "block";

    // Populate the form with the review data
    document.getElementById("reviewId").value = reviewId;
    document.getElementById("reviewText").value = comment;
    document.getElementById("ratingValue").value = rating;

    // Highlight the correct number of stars
    updateStarSelection(rating);
}

function closeReviewModal() {
    // Close the modal
    document.getElementById("reviewModal").style.display = "none";
}

// Update star selection based on the rating
function updateStarSelection(rating) {
    const stars = document.querySelectorAll("#starRating span");
    stars.forEach((star, index) => {
        star.classList.toggle("selected", index < rating); // Highlight stars up to the rating
    });
}

// Add click event listeners to stars for interactive selection
document.querySelectorAll("#starRating span").forEach((star, index) => {
    star.addEventListener("click", function() {
        const rating = index + 1; // Set the rating based on the clicked star index
        document.getElementById("ratingValue").value = rating; // Update hidden input value
        updateStarSelection(rating); // Highlight the selected stars
    });
});
</script>