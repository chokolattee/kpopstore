<?php
session_start();
require('../includes/config.php');

if (!isset($_POST['review_id']) || !is_numeric($_POST['review_id'])) {
    die("Invalid review ID.");
}

$review_id = $_POST['review_id'];
$new_comment = $_POST['review'];
$new_rating = $_POST['rating'];
$new_images = $_FILES['img_path'];

$badWords = ['damn', 'hell', 'ass', 'bitch', 'crap', 'dick', 'fuck', 'shit', 'bastard', 'nigga', 'slut', 'tangina'];

foreach ($badWords as $badWord) {
    $pattern = '/\b' . preg_quote($badWord, '/') . '\b/i';  
    $replacement = str_repeat('*', strlen($badWord)); 
    $new_comment = preg_replace($pattern, $replacement, $new_comment); 
}

$sql = "UPDATE review SET comment = '$new_comment', rate = '$new_rating' WHERE review_id = $review_id";
$result = mysqli_query($conn, $sql);

if (!empty($new_images['name'][0])) {
    $sql_img = "SELECT img_path FROM reviewimg WHERE review_id = $review_id";
    $result_img = mysqli_query($conn, $sql_img);

    if ($result_img && mysqli_num_rows($result_img) > 0) {
        while ($row = mysqli_fetch_assoc($result_img)) {
            $image_path = '../review/images/' . $row['img_path'];
            if (file_exists($image_path)) {
                unlink($image_path); 
            }
        }
    }

    $sql_delete = "DELETE FROM reviewimg WHERE review_id = $review_id";
    $result_delete = mysqli_query($conn, $sql_delete);

    $uploaded_images = [];
    $upload_dir = '../review/images/';
    foreach ($new_images['tmp_name'] as $key => $tmp_name) {
        $image_name = basename($new_images['name'][$key]);
        $target_path = $upload_dir . $image_name;

        if (move_uploaded_file($tmp_name, $target_path)) {
            $uploaded_images[] = $image_name;
            $full_path = $upload_dir . $image_name;

            $sql_insert = "INSERT INTO reviewimg (review_id, img_path) VALUES ($review_id, '$full_path')";
            $result_insert = mysqli_query($conn, $sql_insert);
        }
    }
}


if ($result > 0) {
    $_SESSION['message'] = "Review successfully updated.";
    header("Location: index.php");
    exit();
} else {
    echo "Failed to update the review.";
}
?>