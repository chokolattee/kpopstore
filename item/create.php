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
}

$sql = "SELECT artist_id, artist_name FROM artists";
$result = mysqli_query($conn, $sql);

?>

<body>
    <div class="container">

        <form method="POST" action="store.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Item Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter item name" name="itemname" value="
                <?php
                if (isset($_SESSION['itemname']))
                    echo $_SESSION['itemname'];
                ?>" />

                <small><?php
                        if (isset($_SESSION['itemnameError'])) {
                            echo $_SESSION['itemnameError'];
                            unset($_SESSION['itemnameError']);
                        }
                        ?>
                </small>


                <label for="name">Item Description</label>
                <input type="text" class="form-control" id="desc" placeholder="Enter item description"
                    name="description" value="
                <?php
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
                <select class="form-select" id="artist" name="artist_id">
                    <option value="" disabled selected>Select an artist</option>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo ($row['artist_id']); ?>"><?php echo ($row['artist_name']); ?></option>
                    <?php } ?>
                </select>

                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="Album">Album</option>
                    <option value="Merchandise">Merchandise</option>
                </select>

                <label for="sell">Sell Price</label>
                <input type="text" class="form-control" id="sell" placeholder="Enter item sell price" name="sell_price"
                    value="
                <?php
                if (isset($_SESSION['sellError'])) {
                    echo $_SESSION['sellError'];
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
            <a href="/kpopstore/admin/dashboard.php" role="button" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <?php
    include('../includes/footer.php');
    ?>