<?php
session_start();

include("../includes/config.php");

if (isset($_POST['submit'])) {
  
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    $sql = "SELECT user_id, email, role FROM users WHERE email=? AND password=? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $email, $pass);
    mysqli_stmt_execute($stmt);
    // $result = mysqli_query($conn, $sql);
    // var_dump($result);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $email, $role);
    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_fetch($stmt);
       
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;
        header("Location: ../index.php");
    } else {
        $_SESSION['message'] = 'wrong email or password';
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
?>