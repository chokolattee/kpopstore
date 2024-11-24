<?php
session_start();
include("../includes/config.php");
include("../includes/header.php");
// print_r($_SESSION);


$sql = "SELECT role_id, description FROM role";
$result = mysqli_query($conn, $sql);
?>
<br>
<br>
<div class="signup-form-container" style="height:800px; width: 600px;">
    <?php include("../includes/alert.php"); ?>
    <link rel="stylesheet" href="/kpopstore/includes/style.style.css">
    <form action="store.php" method="POST" enctype="multipart/form-data">
        <h2>WELCOME</h2>
        <h5>친애하는 사용자</h5>
        <h5>dear user</h5>
        <br>
        <label for="fname">First Name</label>
        <input type="text" id="fname" name="fname" required>

        <label for="lname">Last Name</label>
        <input type="text" id="lname" name="lname" required>

        <label for="address">Address</label>
        <input type="text" id="address" name="address" required>

        <label for="contact">Contact Number</label>
        <input type="tel" id="contact" name="contact" placeholder=09XX-XXX-YYYY required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="password2">Confirm Password</label>
        <input type="password" id="password2" name="confirmPass" required>

        <label for="images">Upload User Image</label>
        <input type="file" name="user_img" required /><br />

        <div class="button-container" style="gap:50px; ">
            <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
            <a href="login.php" role="button" class="btn btn-secondary">Cancel</a>
        </div>
</div>
<small><?php
                    if (isset($_SESSION['imageError'])) {
                        echo $_SESSION['imageError'];
                        unset($_SESSION['imageError']);
                    }
                    ?>
</small>

</form>
</di>
</body>

<?php
?>