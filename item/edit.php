<?php
session_start();
include('../includes/headera.php');
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

$item_id = (int) $_GET['id'];

$sql1 = "SELECT * FROM item_details WHERE item_id = {$item_id}";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);

$sql2 = "SELECT artist_id, artist_name FROM artists"; 
$result2 = mysqli_query($conn, $sql2);


?>

<body>
    <div class="container-fluid container-lg">
        <form action="update.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Item Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter item name" name="name"
                    value="<?php echo ($row1['item_name']); ?>" />

                <label for="name">Item Description</label>
                <input type="text" class="form-control" id="name" placeholder="Enter item description"
                    name="description" value="<?php echo ($row1['description']); ?>" />

                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="album" <?php if (($row1['category']) === 'album') echo 'selected'; ?>>Album</option>
                    <option value="merchandise" <?php if (($row1['category']) === 'merchandise') echo 'selected'; ?>>
                        Merchandise</option>
                </select>

                <label for="artist" class="form-label">Select Artist</label>
                <select class="form-select" id="artist" name="artist_id">
                    <option value="" disabled>Select an artist</option>
                    <?php while ($artist = $row2 = mysqli_fetch_assoc($result2)) { ?>
                    <option value="<?php echo $artist['artist_id']; ?>"
                        <?php if (isset($row1['artist_id']) && $artist['artist_id'] == $row1['artist_id']) echo 'selected'; ?>>
                        <?php echo($artist['artist_name']); ?>
                    </option>
                    <?php } ?>
                </select>

                <label for="sell">Sell Price</label>
                <input type="text" class="form-control" id="sell" placeholder="Enter item sell price" name="sell_price"
                    value="<?php echo ($row1['sell_price']); ?>" />

                <label for="qty">Quantity</label>
                <input type="number" class="form-control" id="qty" name="quantity" placeholder=1
                    value="<?php echo ($row1['quantity']); ?>" />

                <label for="images">Upload Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple />
                <small class="form-text text-muted">Select multiple images to add or update existing ones.</small>


                <div class="current-images">
                    <?php
                    $sql_images = "SELECT img_path FROM itemimg WHERE item_id = {$item_id}";
                    $result_images = mysqli_query($conn, $sql_images);
                    while ($img_row = mysqli_fetch_assoc($result_images)) {
                        echo "<img src='" . ($img_row['img_path']) . "' width='100' height='100' style='margin: 10px;' />";
                    }
                    ?>
                </div>
            </div>

            <input type="hidden" name="itemId" value="<?php echo $row1['item_id']; ?>" />

            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            <a href="/kpopstore/admin/dashboard.php" class="btn btn-secondary btn-sm" role="button" aria-disabled="true">Cancel</a>
        </form>
    </div>
</body>

<?php
include('../includes/footer.php');
?>