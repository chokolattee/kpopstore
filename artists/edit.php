<?php
session_start();
include('../includes/header.php');
require('../includes/config.php');

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

$artist_id = (int) $_GET['id'];

$sql = "SELECT * FROM artists WHERE artist_id = $artist_id LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

?>

<body>
    <div class="container-fluid container-lg">

        <form action="update.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="artistName">Artist name</label>
                <input type="text" class="form-control" id="artistName" aria-describedby="emailHelp" placeholder="Enter name" name="artistName" value=<?php print "{$row['artist_name']} ;" ?> />
            <div class="form-group">
                <label for="image">upload image</label>
                <input type="file" class="form-control" id="image" placeholder="image" name="image" accept=".jpeg, .jpg, .png" required>
                    <img width='250' height='250' src= <?php print $row['img_path'] ?> />
            </div>
            <input type="hidden" name="artistId" value=<?php print $row['artist_id'] ?> />


            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            <a href="index.php" class="btn btn-secondary btn-sm " role="button" aria-disabled="true">Cancel</a>
        </form>
    </div>
</body>

<?php
include('../includes/footer.php');
?>