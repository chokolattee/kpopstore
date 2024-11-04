<?php
session_start();
include('../includes/header.php');
include('../includes/config.php');


if(isset($_GET['search'])) {
    $keyword = strtolower(trim($_GET['search']));
}
else {
    $keyword = '';
}


if ($keyword) {
    $sql = "SELECT * FROM item WHERE description LIKE '%{$keyword}%'";
    $result = mysqli_query($conn, $sql);
} else {
    $sql = "SELECT * FROM item";
    $result = mysqli_query($conn, $sql);
}

$sql1 = "SELECT * FROM item_details";
$result1 = mysqli_query($conn, $sql1);
$itemCount = mysqli_num_rows($result1);
?>

<body>
    <a href="create.php" class="btn btn-primary btn-lg" role="button">Add Item</a>
    <h2>Number of Items: <?= $itemCount ?></h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Item ID</th>
                <th>Description</th>
                <th>Category</th>
                <th>Sell Price</th>
                <th>Cost Price</th>
                <th>Artist</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result1)) {
                echo "<tr>";
                echo "<td><img src='" .($row['img_path']) . "' width='150' height='150' /></td>";
                echo "<td>" . ($row['item_id']) . "</td>";
                echo "<td>" . ($row['description']) . "</td>";
                echo "<td>" . ($row['category']) . "</td>";
                echo "<td>" . ($row['sell_price']) . "</td>";
                echo "<td>" . ($row['cost_price']) . "</td>";
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