<?php
session_start();
include('../includes/header.php');
include('../includes/config.php');

// var_dump($_SESSION);

$sql = "SELECT artist_id, artist_name FROM artists";
$result = mysqli_query($conn, $sql);

?>

<body>
    <div class="container">

        <form method="POST" action="store.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Item Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter item name" name="description" value="<?php
                                                                                                                            if (isset($_SESSION['desc']))
                                                                                                                                echo $_SESSION['desc'];
                                                                                                                            ?>" />

                <small><?php
                        if (isset($_SESSION['descError'])) {
                            echo $_SESSION['descError'];
                            unset($_SESSION['descError']);
                        }
                        ?>
                </small>

                <label for="artist" class="form-label">Select Artist</label>
                <select class="form-select" id="artist" name="artist_id" required>
                    <option value="" disabled selected>Select an artist</option>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <option value="<?php echo htmlspecialchars($row['artist_id']); ?>"><?php echo ($row['artist_name']); ?></option>
                    <?php } ?>
                </select>

                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="Album">Album</option>
                    <option value="Merchandise">Merchandise</option>
                </select>

                <label for="cost">Cost Price</label>
                <input type="text" class="form-control" id="cost" placeholder="Enter item cost price" name="cost_price" value="<?php
                                                                                                                                if (isset($_SESSION['cost']))
                                                                                                                                    echo $_SESSION['cost'];
                                                                                                                                ?>">
                <small><?php
                        if (isset($_SESSION['costError'])) {
                            echo $_SESSION['costError'];
                            unset($_SESSION['costError']);
                        }
                        ?></small>

                <label for="sell">Sell Price</label>
                <input type="text" class="form-control" id="sell" placeholder="Enter item sell price" name="sell_price" value="<?php
                                                                                                                                if (isset($_SESSION['sell'])) {
                                                                                                                                    echo $_SESSION['sell'];
                                                                                                                                }
                                                                                                                                ?>">

                <small><?php
                        if (isset($_SESSION['sellError'])) {
                            echo $_SESSION['sellError'];
                            unset($_SESSION['sellError']);
                        }
                        ?></small>

                <label for="qty">quantity</label>
                <input type="number" class="form-control" id="qty" placeholder="1" name="quantity" />

                <label for="images">Upload Images</label>
                <input class="form-control" type="file" name="img_path[]" multiple /><br />

                <small><?php
                        if (isset($_SESSION['imageError'])) {
                            echo $_SESSION['imageError'];
                            unset($_SESSION['imageError']);
                        }
                        ?></small>

            </div>
            <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
            <a href="index.php" role="button" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <?php
    include('../includes/footer.php');
    ?>