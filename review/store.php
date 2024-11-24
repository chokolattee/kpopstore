<?php
session_start();
include('../includes/config.php');

if (isset($_POST['submit'])) {
    $orderinfo_id = $_POST['orderinfo_id'];  
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];  
    $comment = $_POST['review']; 
  
    $badWords = ['damn', 'hell', 'ass', 'fucking', 'kingina', 'bullshit', 'bitch', 'crap', 'dick', 'fuck', 'shit', 'bastard', 'nigga', 'slut', 'tangina'];

foreach ($badWords as $badWord) {
    $pattern = '/\b' . preg_quote($badWord, '/') . '\b/i';  
    $replacement = str_repeat('*', strlen($badWord)); 
    $comment = preg_replace($pattern, $replacement, $comment); 
}

    $imagePaths = [];
    if (isset($_FILES['img_path']) && !empty($_FILES['img_path']['name'][0])) {
        foreach ($_FILES['img_path']['name'] as $key => $name) {
            if ($_FILES['img_path']['type'][$key] == "image/jpeg" || $_FILES['img_path']['type'][$key] == "image/png") {
                $source = $_FILES['img_path']['tmp_name'][$key];
                $target = '../review/images/' . basename($name);

               
                if (move_uploaded_file($source, $target)) {
                    $imagePaths[] = $target; 
                } else {
                    $_SESSION['imageError'] = "Couldn't copy the image file.";
                    header("Location: view_order.php");
                    exit();
                }
            } else {
                $_SESSION['imageError'] = "Wrong file type. Only JPG and PNG files are allowed.";
                header("Location: view_order.php");
                exit();
            }
        }
    }

    $query = "INSERT INTO review (orderinfo_id, user_id, rate, comment) 
              VALUES ('$orderinfo_id', '$user_id', '$rating', '$comment')"; 
    $result = mysqli_query($conn, $query);

    if ($result) {
        $review_id = mysqli_insert_id($conn);

        foreach ($imagePaths as $imgPath) {
            $sql_img = "INSERT INTO reviewimg(review_id, img_path) VALUES('{$review_id}', '{$imgPath}')";
            $result1 = mysqli_query($conn, $sql_img);
        }

        $_SESSION['message'] = "Review successfully added.";
        header("Location: index.php");
    } else {
        $_SESSION['message'] = "Error adding review.";
        header("Location: /kpopstore/view_order.php");
    }
}
?>