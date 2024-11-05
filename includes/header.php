<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-Pop Store</title>
    <link rel="stylesheet" href="/kpopstore/includes/style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header class="header">
        <div class="auth-buttons">
            <button onclick="window.location.href='/kpopstore/user/login.php'">Log In</button>
            <button onclick="window.location.href='signup.html'">Sign Up</button>
            <button onclick="window.location.href='/kpopstore/user/logout.php'">Logout</button>
            <button class="cart-button" onclick="window.location.href='cart.html'">
                <i class="fas fa-shopping-cart"></i>
            </button>
        </div>
        <h1> HALLYU </h1>
        <div class="header-content">
            <nav class="top-nav">
                <a href="/kpopstore">Home</a>

                <!-- <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="/kpopstore/artists/create.php">Artists</a>
                    <?php elseif ($_SESSION['role'] === 'user'): ?>
                        <a href="/kpopstore/artists/index.php">Artists</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/kpopstore/user/login.php">Artists</a>
                <?php endif; ?> -->

                <a href="#">Albums</a>
                <a href="#">Merch</a>
                <a href="#">Contact Us</a>
            </nav>
        </div>

        <form class="search-form" action="search.php" method="GET">
            <button type="submit">Search</button>
            <input type="text" name="query" placeholder=" " required>
        </form>


    </header>
    <main>
        <section class="intro">
            <br>
            <h2>Welcome to Your K-Pop Dream Store!</h2>
            <h3>스태니를 환영합니다</h3>
            <br>
            <p>Find your favorite albums, merchandise, and more.</p>
        </section>
        <br>
        <br>

        <script src="../includes/style/scripts.js"></script>
</body>

</html>