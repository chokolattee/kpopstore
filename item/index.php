<?php
session_start();
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

if(isset($_GET['search'])) {
    $keyword = strtolower(trim($_GET['search']));
}
else {
    $keyword = '';
}


if ($keyword) {
    $sql1 = "SELECT * FROM item_details WHERE item_name LIKE '%{$keyword}%'";
} else {
    $sql1 = "SELECT * FROM item_details";
}
$result1 = mysqli_query($conn, $sql1);
$itemCount = mysqli_num_rows($result1);

?>

<body>
    <a href="/kpopstore/item/create.php" class="btn btn-primary btn-lg" role="button">Add Item</a>
    <h2>Number of Items: <?= $itemCount ?></h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Sell Price</th>
                <th>Artist</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result1)) {
                echo "<tr>";

                echo "<td>";
                $images = explode(',', $row['images']);
                foreach ($images as $image) {
                    echo "<img src='" . ($image) . "' width='100' height='100' style='margin: 5px;' />";
                }
                echo "</td>";

                echo "<td>" . ($row['item_id']) . "</td>";
                echo "<td>" . ($row['item_name']) . "</td>";
                echo "<td>" . ($row['description']) . "</td>";
                echo "<td>" . ($row['category']) . "</td>";
                echo "<td>" . ($row['sell_price']) . "</td>";
                echo "<td>" . ($row['artist_name']) . "</td>"; 
                echo "<td>" . ($row['quantity']) . "</td>"; 
                echo "<td>
                        <a href='edit.php?id={$row['item_id']}'><i class='fa-regular fa-pen-to-square' style='color: blue'></i></a>
                        <a href='delete.php?id={$row['item_id']}'><i class='fa-solid fa-trash' style='color: red'></i></a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>