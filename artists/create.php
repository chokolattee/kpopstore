<?php
session_start();
print_r($_SESSION);
include('../includes/header.php');
include('../includes/config.php');
// if(! isset($_SESSION['user_id'])){
//     $_SESSION['message'] = 'Login first to access resource';
//     header("Location: ../user/login.php");
    
// }
// else { 
    ?>
<body>
    <div class="container-fluid container-lg">
        <form action="store.php" method="POST" enctype="multipart/form-data" >
            <div class="form-group">
                <label for="artistName">Artist Name</label>
                <input type="text" class="form-control" id="artistName" placeholder="Enter name"
                    name="artistName">
                    <input class="form-control" type="file" name="img_path" accept=".jpeg, .jpg, .png" required /><br />
                <small><?php 
                if (isset($_SESSION['imageError'])) {
                    echo $_SESSION['imageError'];
                    unset($_SESSION['imageError']);
                }
                ?></small>

            </div>
            <button type="submit" class="btn btn-primary"name="submit" value="submit">Submit</button>
            <a href="index.php" role="button" class="btn btn-secondary">Cancel</a>
        </form>
        </div>
</body>

<?php
include('../includes/footer.php');
            // }
?>
