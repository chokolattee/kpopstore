<?php
// Start the session

// Simulate user login status (replace this with your actual authentication logic)
$user_logged_in = isset($_SESSION['user_id']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-Pop Store</title>
    <link rel="stylesheet" href="/kpopstore/includes/style/style.css">
    <link rel=" stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>

<body>
    <header class="header">
        <div class="auth-buttons">
            <button onclick=" window.location.href='/kpopstore/user/login.php'">Log
                In</button>
            <button onclick=" window.location.href='/kpopstore/user/register.php'">Sign Up</button>
            <?php if ($user_logged_in): ?>
            <button onclick="window.location.href='/kpopstore/user/logout.php'">Logout</button>
            <?php endif; ?>

            <button class=" cart-button" onclick="window.location.href='/kpopstore/view_cart.php'">
                <i class="fas fa-shopping-cart"></i>
            </button>
            <button class="order-button" onclick="window.location.href='/kpopstore/view_order.php'">
                <i class="fas fa-box"></i>
            </button>
            <button class="reviews-button" onclick="window.location.href='/kpopstore/review/index.php'">
                <i class="fas fa-star"></i>
            </button>
            <button class="profile-button" onclick="window.location.href='/kpopstore/user/profile.php'">
                <i class="fas fa-user"></i>
            </button>

        </div>
        <h1 style="font-family:'BOOKMAN OLD STYLE', sans-serif;"> HALLYU </h1>

        <nav class="top-nav">
            <a href="/kpopstore">Home</a>
            <a href="/kpopstore/artists/list.php">Artist</a>
            <a href="/kpopstore/album.php">Albums</a>
            <a href="/kpopstore/merch.php">Merch</a>
            <a href="/kpopstore/contact.php">Contact Us</a>
        </nav>


        <form class="search-form" action="search.php" method="GET">
            <button type="submit">Search</button>
            <input type="text" name="query" placeholder=" " required>
        </form>


    </header>
    <script src="../includes/style/scripts.js"></script>
    <script>
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
    </script>
</body>

<style>
.top-nav {
    display: flex;
    justify-content: center;
    gap: 20px;
    font-family: serif;

}

.top-nav a {
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
    border-bottom: 2px solid transparent;
    transition: border-bottom 0.3s ease, color 0.3s ease;
}
</style>

</html>