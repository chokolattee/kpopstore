<!-- <?php
session_start();
include("../includes/config.php");

if (isset($_POST['submit'])) {

    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    $sql_user = "SELECT user_id, email FROM user WHERE email=? AND password=? LIMIT 1";
    $stmt_user = mysqli_prepare($conn, $sql_user);
    mysqli_stmt_bind_param($stmt_user, 'ss', $email, $pass);
    mysqli_stmt_execute($stmt_user);
    mysqli_stmt_store_result($stmt_user);
    mysqli_stmt_bind_result($stmt_user, $user_id, $email, $role);

    // Check if a user exists
    if (mysqli_stmt_num_rows($stmt_user) === 1) {
        mysqli_stmt_fetch($stmt_user);
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;
        header("Location: ../index.php");
    } else {
        // If not found in users, check admins table
        $sql_admin = "SELECT admin_id, email, role FROM admins WHERE email=? AND password=? LIMIT 1";
        $stmt_admin = mysqli_prepare($conn, $sql_admin);
        mysqli_stmt_bind_param($stmt_admin, 'ss', $email, $pass);
        mysqli_stmt_execute($stmt_admin);
        mysqli_stmt_store_result($stmt_admin);
        mysqli_stmt_bind_result($stmt_admin, $admin_id, $email, $role);

        if (mysqli_stmt_num_rows($stmt_admin) === 1) {
            mysqli_stmt_fetch($stmt_admin);
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $admin_id;
            $_SESSION['role'] = $role;
            header("Location: ../admin_dashboard.php");
        } else {
            $_SESSION['message'] = 'Wrong email or password';
        }
    }
}
?>

   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../includes/style/style.css">
</head>
<body>
<header class="header">
        <h1>HALLYU</h1>
    </header>
    
    <div class="login-form-container">
        <h2><b>Log In</b></h2>
        <br>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="error-message">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); // Clear message after displaying
                ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            
            <button type="submit" name="submit">Log In</button>
        </form>
        <br>
        <br>
        <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
    </div>
</body>
</html>
<?php
include("../includes/footer.php");
?> -->