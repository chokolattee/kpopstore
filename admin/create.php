<?php
session_start();
include("../includes/config.php");


if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please log in to access resources';
    header("Location: /kpopstore/user/login.php");
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id' AND r.role_id = 1 LIMIT 1";
$result= mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['message'] = 'You must be logged in as admin to access this page.';
    header("Location: /kpopstore/user/login.php");
}

$sql = "SELECT role_id, description FROM role";
$result = mysqli_query($conn, $sql);
?>

<body
    style="background-color:#d8bfd8; margin: 0; height: 100vh; display: flex; justify-content: center; align-items: center;">
    <div class="container-fluid container-lg"
        style="width: 50%; background-color: #f3e4f7; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">
        <h1 style="text-align: center; font-size: 40px; color: #6a0572;">CREATE ADMIN</h1>
        <?php include("../includes/alert.php"); ?>
        <form action="store.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" required>

                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" required>

                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>

                <label for="contact" class="form-label">Contact Number</label>
                <input type="tel" class="form-control" id="contact" name="contact" placeholder="09XX-XXX-YYYY" required>

                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>

                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>

                <label for="password2" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password2" name="confirmPass" required>

                <label for="images" class="form-label">Upload User Image</label>
                <input class="form-control" type="file" name="user_img" />
                <br />

                <small>
                    <?php
                    if (isset($_SESSION['imageError'])) {
                        echo $_SESSION['imageError'];
                        unset($_SESSION['imageError']);
                    }
                    ?>
                </small>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" class="btn btn-primary" name="submit" value="submit"
                    style="margin-right: 10px;">Submit</button>
                <a href="login.php" role="button" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>