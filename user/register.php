<?php
session_start();
include("../includes/config.php");
include("../includes/header.php");
// print_r($_SESSION);
?>

<div class="container-fluid container-lg">
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
            <input type="text" class="form-control" id="contact" name="contact" required>

            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
 
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>

            <label for="password2" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password2" name="confirmPass" required>

            <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="user" selected>User</option>
                    <option value="admin">Admin</option>
            </select>

            <label for="images">Upload User Image</label>
                <input class="form-control" type="file" name="user_img" required/><br />

                <small><?php
                        if (isset($_SESSION['imageError'])) {
                            echo $_SESSION['imageError'];
                            unset($_SESSION['imageError']);
                        }
                        ?>
                </small>
    </div>
    <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
    <a href="login.php" role="button" class="btn btn-secondary">Cancel</a>
        </form>
        </div>
</body>

<?php
include("../includes/footer.php");
?>