<?php
session_start();
include('../includes/headera.php');
include('../includes/config.php');

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
    exit();
}
?>

<body>
    <div class="container-fluid container-lg">
        <form action="store.php" method="POST" enctype="multipart/form-data" >
            <div class="form-group">
                <label for="artistName">Artist Name</label>
                <input type="text" class="form-control" id="artistName" placeholder="Enter name"
                    name="artistName">
                <label for="image">upload image</label>
                    <input class="form-control" type="file" name="img_path" accept=".jpeg, .jpg, .png" required /><br />
                <small><?php 
                if (isset($_SESSION['imageError'])) {
                    echo $_SESSION['imageError'];
                    unset($_SESSION['imageError']);
                }
                ?></small>

            </div>
            <button type="submit" class="btn btn-primary"name="submit" value="submit">Submit</button>
            <a href="/kpopstore/admin/dashboard.php" role="button" class="btn btn-secondary">Cancel</a>
        </form>
        </div>
</body>

<?php
include('../includes/footer.php');
            // }
?>
