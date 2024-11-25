<?php
session_start();

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
    exit();
}
include('../includes/headera.php');

$item_id = (int) $_GET['id'];

$sql1 = "SELECT * FROM item_details WHERE item_id = {$item_id}";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);

$sql = "SELECT artist_id, artist_name FROM artists";
$result = mysqli_query($conn, $sql);


?>

<body>
<body style="background-color:#d8bfd8; margin: 0; min-height: 100vh;">
    <div class="container-fluid container-lg" style="width: 50%; background-color: #f3e4f7; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0,
    0, 0.2); margin: 20px auto;">
        <div style="text-align: center; padding: 20px; background-color: #f3e4f7;">
            <h1 style="font-size: 40px; color: #6a0572;">EDIT ITEM</h1>
        </div>
        <?php include("../includes/alert.php"); ?>
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
                <select class="form-select" id="artist" name="artist_name">
                    <option value="" disabled>Select an artist</option>
                    <?php while ($artist_row = mysqli_fetch_assoc($result)) { ?>
                        <option value="<?php echo htmlspecialchars($artist_row['artist_name']); ?>" 
                                <?php if ($artist_row['artist_name'] === $row1['artist_name']) echo 'selected'; ?>>
                            <?php echo ($artist_row['artist_name']); ?>
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
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" class="btn btn-primary" name="submit" value="submit"
                    style="margin-right: 10px;">Submit</button>
                <a href="index.php" role="button" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>


<?php
include('../includes/footer.php');
?>