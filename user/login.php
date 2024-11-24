<?php
session_start();

include("../includes/header.php");
include("../includes/config.php");

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $pass = sha1(trim($_POST['password']));
    $sql = "
        SELECT u.user_id, u.email, r.description, s.status_id
        FROM user u
        JOIN role r ON u.role_id = r.role_id
        JOIN user_status s ON u.status_id = s.status_id
        WHERE u.email = ? AND u.password = ? 
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $email, $pass);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $email, $description, $status_id);

    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_fetch($stmt);

        if ($status_id != 1) {
            $_SESSION['message'] = 'Your account is deactivated. Please contact support.';
            header("Location: login.php");
            exit;
        }

        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $description; 

        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['message'] = 'Wrong email or password';
    }
}
?>

<div class="login-form-container">
    <h2><b>Log In</b></h2>
    <br>
    <?php include("../includes/alert.php"); 
    if (isset($_SESSION['message'])) {
        $_SESSION['message'];
       unset($_SESSION['message']);
       }
       ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" />

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" />
        <br>
        <button type="submit" class="btn btn-primary" name="submit">Log In</button>
    </form>
    <br><br>
    <p>Not a member? <a href="register.php">Sign Up</a></p>
</div>

<?php
include("../includes/footer.php");
?>