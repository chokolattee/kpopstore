<?php
session_start();

require("../includes/config.php");

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
}
include("../includes/headera.php");
?>

<body>
    <div class="container my-5" style="height:max-content" ;>
        <h1 class="text-center mb-4">Artists</h1>
        <a href="/kpopstore/artists/create.php" class="btn btn-primary mb-3">Add Artist</a>

        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php
            $sql = "SELECT * FROM artists ORDER BY artist_id DESC";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='col'>
                        <div class='card'>
                            <img src='{$row['img_path']}' class='card-img-top' alt='{$row['artist_name']}'>
                            <div class='card-body'>
                                <h5 class='card-title'>{$row['artist_name']}</h5>
                                <div class='d-flex justify-content-between'>
                                    <a href='edit.php?id={$row['artist_id']}' class='btn btn-warning btn-sm'>
                                        <i class='fa-regular fa-pen-to-square' aria-hidden='true'></i> Edit
                                    </a>
                                    <a href='delete.php?id={$row['artist_id']}' class='btn btn-danger btn-sm'>
                                        <i class='fa-regular fa-trash-can' aria-hidden='true'></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>";
            }
            ?>
        </div>
    </div>
</body>

<?php
?>

<style>
    .card-img-top {
        object-fit: cover;
        height: 200px;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
    }

    .btn-sm {
        font-size: 0.9rem;
        padding: 4px 10px;
    }
</style>