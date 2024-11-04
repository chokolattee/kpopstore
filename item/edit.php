<?php
session_start();
include('../includes/header.php');
require('../includes/config.php');

$item_id = (int) $_GET['id'];

$sql1 = "SELECT * FROM item_details WHERE item_id = {$item_id}";
$result1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_assoc($result1);

$sql2 = "SELECT artist_id, artist_name FROM artists"; // Specify the fields you need
$artistsResult = mysqli_query($conn, $sql2);
?>

<body>
    <div class="container-fluid container-lg">
        <form action="update.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Item Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter item name" name="description" value="<?php echo htmlspecialchars($row['description']); ?>" />

                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="album" <?php if (strtolower($row['category']) === 'album') echo 'selected'; ?>>Album</option>
                    <option value="merchandise" <?php if (strtolower($row['category']) === 'merchandise') echo 'selected'; ?>>Merchandise</option>
                </select>

                <label for="artist" class="form-label">Select Artist</label>
                <select class="form-select" id="artist" name="artist_id" required>
                    <option value="" disabled>Select an artist</option>
                    <?php while ($artist = mysqli_fetch_assoc($artistsResult)) { ?>
                        <option value="<?php echo $artist['artist_id']; ?>" <?php if ($artist['artist_id'] == $row['artist_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($artist['artist_name']); ?>
                        </option>
                    <?php } ?>
                </select>

                <label for="cost">Cost Price</label>
                <input type="text" class="form-control" id="cost" placeholder="Enter item cost price" name="cost_price" value="<?php echo htmlspecialchars($row['cost_price']); ?>" />

                <label for="sell">Sell Price</label>
                <input type="text" class="form-control" id="sell" placeholder="Enter item sell price" name="sell_price" value="<?php echo htmlspecialchars($row['sell_price']); ?>" />

                <label for="qty">Quantity</label>
                <input type="number" class="form-control" id="qty" placeholder="1" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>" />

                <input type="file" class="form-control" id="image" name="image" />
                <img width='250' height='250' src="<?php echo htmlspecialchars($row['img_path']); ?>" />
            </div>

            <input type="hidden" name="itemId" value="<?php echo $row['item_id']; ?>" />

            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            <a href="index.php" class="btn btn-secondary btn-sm" role="button" aria-disabled="true">Cancel</a>
        </form>
    </div>
</body>

<?php
include('../includes/footer.php');
?>