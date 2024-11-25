<?php
session_start();

require('../includes/config.php');
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please log in to access resources';
    header("Location: /kpopstore/user/login.php");
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id' AND r.role_id = 1 LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['message'] = 'You must be logged in as admin to access this page.';
    header("Location: /kpopstore/user/login.php");
    exit();
}

include('../includes/headera.php');

$artist_id = (int) $_GET['id'];

$sql = "SELECT * FROM artists WHERE artist_id = $artist_id LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

?>

<body>

    <body style="background-color:#d8bfd8; margin: 0; min-height: 100vh;">
        <div class="container-fluid container-lg" style="width: 50%; background-color: #f3e4f7; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); margin: 20px auto;">
            <div style="text-align: center; padding: 20px; background-color: #f3e4f7;">
                <h1 style="font-size: 40px; color: #6a0572;">EDIT ARTIST</h1>
            </div>
            <?php include("../includes/alert.php"); ?>
            <form action="update.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="artistName">Artist Name</label>
                    <input type="text" class="form-control" id="artistName" name="artistName" value="<?php echo htmlspecialchars($row['artist_name']); ?>" placeholder="Enter name">

                    <label for="image">Upload Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept=".jpeg, .jpg, .png">
                    <img src="<?php echo $row['img_path']; ?>" alt="Current Artist Image" style="max-width: 250px; max-height: 250px; display: block; margin: 10px auto;">
                </div>
                <input type="hidden" name="artistId" value="<?php echo $row['artist_id']; ?>" />

                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    <a href="index.php" role="button" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </body>

    <?php
    include('../includes/footer.php');
    ?>