<?php
session_start();

include("../includes/header.php");
include("../includes/config.php");
include("../includes/alert.php");

if (!isset($_SESSION['user_id'])) {
  $_SESSION['message'] = 'Please log in to access resources';
  header("Location: /kpopstore/user/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id' AND r.role_id IN (1,2) LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
  $_SESSION['message'] = 'You must be logged in to access this page.';
  header("Location: /kpopstore/user/login.php");
  exit;
}

$sql1 = "SELECT * FROM user WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql1);
$row = mysqli_fetch_assoc($result);
$imgPath = $row['user_img'];
?>

<body style="background-color:#dec9e6">;
    <br>
    <br>
    <div class="container my-container mt-5">
        <!-- Toggle Buttons for User Info and Security -->
        <div class="btn-group mb-3" role="group" aria-label="Info and Security">
            <button type="button" class="btn btn-primary" id="userInfoBtn">Personal Info</button>
            <button type="button" class="btn btn-secondary" id="securityBtn">Security</button>
        </div>

        <!-- User Info Section -->
        <form action="update_info.php" method="POST" enctype="multipart/form-data">
            <div id="userInfoSection" class="section">
                <br>
                <h4 class="panel-title text-center mb-4">Personal Info</h4>
                <br>
                <div class="form-group">
                    <div class="mb-3 text-center">

                        <div class="mb-3">
                            <?php echo "<img class='rounded-circle' style='width: 70px; height: 70px;' src='/kpopstore/user/$imgPath'/>"; ?>
                            <br>
                            <label for="user_img" class="form-label">Profile Picture</label>
                            <br>
                        </div>
                        <br>
                        <input class="form-control" type="file" name="user_img" id="user_img">
                        <small>
                            <?php
                            if (isset($_SESSION['imageError'])) {
                                echo $_SESSION['imageError'];
                                unset($_SESSION['imageError']);
                            }
                            ?>
                        </small>
                    </div>
                    <br>
                    <label for="fname">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname"
                        value="<?php echo ($row['fname']); ?>" />

                    <label for="lname">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname"
                        value="<?php echo ($row['lname']); ?>" />

                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address"
                        value="<?php echo ($row['address']); ?>" />

                    <label for="contact">Contact Number</label>
                    <input type="text" class="form-control" id="contact" name="contact"
                        value="<?php echo ($row['contact_number']); ?>" />
                    <br>
                    <div class="button-row">
                        <button type="submit" name="update_info" class="btn btn-primary">Submit</button>
                        <button type="submit" a href="/kpopstore/index.php" class="btn btn-secondary mt-3">Cancel</ba>
                    </div>

                </div>
            </div>
        </form>

        <!-- Security Section -->
        <form action="update_security.php" method="POST" enctype="multipart/form-data">
            <div id="securitySection" class="section" style="display: none;">
                <h4 class="panel-title text-center mb-4">Security</h4>
                <div class="form-group">
                    <label for="email">E-mail Address</label>
                    <input type="text" class="form-control" id="email" name="email"
                        value="<?php echo ($row['email']); ?>" />

                    <label for="oldPass">Current Password</label>
                    <input type="password" class="form-control" id="oldPass" name="oldPass" />

                    <label for="newPass">New Password</label>
                    <input type="password" class="form-control" id="newPass" name="newPass" />

                    <button type="submit" name="update_security" class="btn btn-primary mt-3">Submit</button>
                    <a href="/kpopstore/index.php" class="btn btn-secondary mt-3" id="cancelBtn">Cancel</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Include footer -->


    <script>
    // Toggle between User Info and Security sections
    document.getElementById('userInfoBtn').addEventListener('click', function() {
        document.getElementById('userInfoSection').style.display = 'block';
        document.getElementById('securitySection').style.display = 'none';
    });

    document.getElementById('securityBtn').addEventListener('click', function() {
        document.getElementById('userInfoSection').style.display = 'none';
        document.getElementById('securitySection').style.display = 'block';
    });
    </script>
</body>



<style>
.my-container {
    background-color: #e1b7eb;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    max-width: 600px;
    height: 600px;
    margin: 0 auto;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2), 0 4px 6px rgba(0, 0, 0, 0.15);

}

.body {
    background-color: #ddcae6;
}


.panel-title {
    font-size: 1.5rem;
    font-weight: bold;
    font-family: fantasy;
    font-size: 35px;
    color: indigo;
}


.btn-primary {
    background-color: #4CAF50 !important;
    border-color: #4CAF50 !important;
    color: white !important;
}

.btn-primary:hover {
    background-color: #45a049 !important;
    border-color: #45a049 !important;
}

.btn-secondary {
    background-color: #d9534f !important;
    border-color: #d9534f !important;
    color: white !important;
}

.btn-secondary:hover {
    background-color: #c9302c !important;
    border-color: #c9302c !important;
}

.form-control {
    border-radius: 5px;
    border: 1px solid #ccc;
    padding: 10px;
}

#userInfoBtn,
#securityBtn {
    width: 150px;
    height: 35px;
    font-size: 16px;
    padding: 0px;
    border-radius: 5px;

}

.info {
    display: flex;
    justify-content: center;
}

img.rounded-circle {
    border: 2px solid #ccc;
    margin-bottom: 10px;
}

.info {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 auto;
    width: 100%;
    max-width: 400px;

}

.form-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 10px;
    width: 100%;

}

.form-group label {
    margin-bottom: 5px;
    font-size: 15px;
}

.form-group input {
    width: 100%;
    max-width: 300px;
    margin-bottom: 5px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

}

.button-row {
    display: flex;
    gap: 10px;
    /* Adjust spacing between buttons */
    justify-content: center;
    /* Center the buttons */
    align-items: center;
    /* Vertically align buttons */
}
</style>