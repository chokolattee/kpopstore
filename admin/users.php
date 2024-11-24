<?php
session_start();
include("../includes/config.php");


if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please log in to access resources';
    header("Location: /kpopstore/user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id' AND r.role_id = 1 LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['message'] = 'You must be logged in as admin to access this page.';
    header("Location: /kpopstore/user/login.php");
    exit;
}

if (isset($_POST['change_role']) && isset($_POST['target_user_id']) && isset($_POST['new_role_id'])) {
    $target_user_id = $_POST['target_user_id'];
    $new_role_id = $_POST['new_role_id'];

    $role_check_sql = "SELECT 1 FROM role WHERE role_id = $new_role_id";
    $role_check_result = mysqli_query($conn, $role_check_sql);

    if (mysqli_num_rows($role_check_result) == 0) {
        $_SESSION['message'] = 'Invalid role selected.';
    } else {
        $update_role_sql = "UPDATE user SET role_id = $new_role_id WHERE user_id = $target_user_id";
        if (mysqli_query($conn, $update_role_sql)) {
            $_SESSION['success'] = 'User role updated successfully.';
        } else {
            $_SESSION['message'] = 'Error in updating role: ' . mysqli_error($conn);
        }
    }
}

if (isset($_POST['change_status']) && isset($_POST['target_user_id']) && isset($_POST['new_status_id'])) {
    $target_user_id = $_POST['target_user_id'];
    $new_status_id = $_POST['new_status_id'];

    $status_check_sql = "SELECT 1 FROM user_status WHERE status_id = $new_status_id";
    $status_check_result = mysqli_query($conn, $status_check_sql);

    if (mysqli_num_rows($status_check_result) == 0) {
        $_SESSION['message'] = 'Invalid status selected.';
    } else {
        $update_status_sql = "UPDATE user SET status_id = $new_status_id WHERE user_id = $target_user_id";
        if (mysqli_query($conn, $update_status_sql)) {
            $_SESSION['success'] = 'User status updated successfully.';
        } else {
            $_SESSION['message'] = 'Error in updating status: ' . mysqli_error($conn);
        }
    }
}

$sql = "SELECT u.user_id, CONCAT(u.fname, ' ', u.lname) AS name, u.email, r.description AS role, s.status_name, u.status_id, u.role_id 
        FROM user u 
        INNER JOIN role r ON u.role_id = r.role_id 
        INNER JOIN user_status s ON u.status_id = s.status_id";
$result = mysqli_query($conn, $sql);


$role_sql = "SELECT * FROM role";
$role_result = mysqli_query($conn, $role_sql);
$roles = [];
while ($role = mysqli_fetch_assoc($role_result)) {
    $roles[] = $role;
}

$status_sql = "SELECT * FROM user_status";
$status_result = mysqli_query($conn, $status_sql);
$statuses = [];
while ($status = mysqli_fetch_assoc($status_result)) {
    $statuses[] = $status;
}
?>

<h2>User Management</h2>
<?php include("../includes/alert.php"); ?>

<table class="table table-striped table-bordered">

    <thead>
        <tr>
            <th style=" width: 50px; background-color:#D8BFD8;">ID</th>
            <th style="width: 150px;background-color:#D8BFD8;">Name</th>
            <th style="width: 100px;background-color:#D8BFD8;">Email</th>
            <th style="width: 50px;background-color:#D8BFD8;">Role</th>
            <th style="width: 50px;background-color:#D8BFD8;">Status</th>
            <th style="width: 200px;background-color:#D8BFD8;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td style="background-color:#F1EAFF" ;><?= $row['user_id'] ?></td>
            <td style="background-color:#F1EAFF"><?= $row['name'] ?></td>
            <td style="background-color:#F1EAFF"><?= $row['email'] ?></td>
            <td style="background-color:#F1EAFF"><?= $row['role'] ?></td>
            <td style="background-color:#F1EAFF"><?= $row['status_name'] ?></td>
            <td>

                <div class="button-group">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" style="display:inline;">
                        <input type="hidden" name="target_user_id" value="<?= $row['user_id'] ?>">
                        <select name="new_status_id">
                            <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['role_id'] ?>"
                                <?= $role['role_id'] == $row['role_id'] ? 'selected' : '' ?>>
                                <?= $role['description'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="change_role" class="btn btn-info btn-sm" style="margin-left:50px;">
                            Update
                            Role</button>
                    </form>

                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" style="display:inline;">
                        <input type="hidden" name="target_user_id" value="<?= $row['user_id'] ?>">
                        <select name="new_status_id">
                            <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status['status_id'] ?>"
                                <?= $status['status_id'] == $row['status_id'] ? 'selected' : '' ?>>
                                <?= $status['status_name'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="change_status" class="btn btn-primary btn-sm"
                            style="margin-left:10px; padding:5px;">Update
                            Status</button>
                    </form>
                    <form action="delete.php" method="POST" style="display:inline;">
                        <input type="hidden" name="target_user_id" value="<?= $row['user_id'] ?>">
                        <button type="submit" name="delete" class="btn btn-danger btn-sm"
                            style="margin-left:130px;">Remove
                            User</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include("../includes/footer.php"); ?>

<style>
.button-group {
    display: flex;
    flex-direction: column;
    /* Stack items vertically */
    align-items: start;
    /* Align buttons to the start (left-aligned) */
    gap: 0.5rem;
    /* Add some space between buttons */
}

.button-group form {
    margin: 0;
    /* Remove default margin from forms */
}
</style>