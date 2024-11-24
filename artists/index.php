<?php
session_start(); 

require("../includes/config.php");

// CREATE VIEW reviewdetails AS
// SELECT 
//     r.*, 
//     od.full_name,  
//     GROUP_CONCAT(DISTINCT ri.img_path) AS img_paths, 
//     GROUP_CONCAT(DISTINCT i.item_id) AS item_ids
// FROM 
//     review r
// JOIN 
//     orderdetails od ON r.user_id = od.user_id
// JOIN 
//     orderline ol ON ol.orderinfo_id = r.orderinfo_id
// JOIN 
//     orderinfo oi ON oi.orderinfo_id = ol.orderinfo_id
// JOIN 
//     item i ON i.item_id = ol.item_id
// LEFT JOIN 
//     reviewimg ri ON r.review_id = ri.review_id
// GROUP BY 
//     r.review_id, r.orderinfo_id;



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

?>

<body>
    <h1>Artists</h1>
    <a href="/kpopstore/artists/create.php" class="btn btn-primary mb-3">Add Artist</a>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>Artist ID</th>
                <th>Artist Name</th>
                <th>Image</th>
                <!-- <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?> -->
                <th>Action</th>
                <!-- <?php endif; ?> -->
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM artists ORDER BY artist_id DESC";
            $result = mysqli_query($conn, $sql);
            

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['artist_id']}</td>
                        <td>{$row['artist_name']}</td>
                        <td><img width='250' height='250' src='{$row['img_path']}' /></td>";
                    echo "<td>
                            <a href='edit.php?id={$row['artist_id']}'><i class='fa-regular fa-pen-to-square' aria-hidden='true' style='font-size:24px'></i></a>
                            <a href='delete.php?id={$row['artist_id']}'><i class='fa-regular fa-trash-can' aria-hidden='true' style='font-size:24px; color:red'></i></a>
                          </td>";
                }
                echo "</tr>";
            ?>
        </tbody>
    </table>
</body>

<?php

?>