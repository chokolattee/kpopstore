<?php
session_start(); // Make sure the session is started
include('../includes/header.php');
require("../includes/config.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Login first to access resource';
    header("Location: ../user/login.php");
    exit; // Important to prevent further execution
}

// Display any session messages
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>{$_SESSION['message']}</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
    unset($_SESSION['message']); // Clear message after displaying
}
?>
<body>
    <h1>Artists</h1>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>Artist ID</th>
                <th>Artist Name</th>
                <th>Image</th>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <th>Action</th>
                <?php endif; ?>
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
                // Show action links only for admins
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    echo "<td>
                            <a href='edit.php?id={$row['artist_id']}'><i class='fa-regular fa-pen-to-square' aria-hidden='true' style='font-size:24px'></i></a>
                            <a href='delete.php?id={$row['artist_id']}'><i class='fa-regular fa-trash-can' aria-hidden='true' style='font-size:24px; color:red'></i></a>
                          </td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>

<?php
include('../includes/footer.php');
?>





