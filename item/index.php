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
    $sql = "SELECT * FROM item_details WHERE description LIKE '%{$keyword}%'";
} else {
    $sql = "SELECT * FROM item_details";
}
$result = mysqli_query($conn, $sql);
$itemCount = mysqli_num_rows($result);
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
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";

                echo "<td>";
                $images = explode(',', $row['images']);
                foreach ($images as $image) {
                    echo "<img src='" . ($image) . "' width='100' height='100' style='margin: 5px;' />";
                }
                echo "</td>";

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
