<?php
session_start();
include("../includes/config.php");


if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please log in to access resources';
    header("Location: /kpopstore/user/login.php");
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id' AND r.role_id = 1 LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['message'] = 'You must be logged in as admin to access this page.';
    header("Location: /kpopstore/user/login.php");
    exit();
}

$sql = "SELECT role_id, description FROM role";
$result = mysqli_query($conn, $sql);

include("../includes/headera.php");
include("../includes/alert.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
</head>

<body style="background-color:#DFD2F4">
    <br>
    <h1 style="font-family: 'BOOKMAN OLD STYLE', sans-serif; font-size: 45px; text-align: center;"></h1>
    <h3 style="text-align:center; font-size: 30px;"></h3>

    <div class="content-area">
        <p></p>
    </div>
    </div>

    <!-- Page Content -->
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        </nav>
    </div>
    </div>

    <!-- jQuery and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
    </script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script>

    <!-- Sidebar Toggle jQuery -->
    <script>
        $(document).ready(function() {
            // Sidebar toggle functionality
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
                if ($('#sidebar').hasClass('active')) {
                    $('#sidebarCollapse').css('opacity',
                        '0'); // Hide the hamburger icon when sidebar is open
                } else {
                    $('#sidebarCollapse').css('opacity',
                        '1'); // Show the hamburger icon when sidebar is closed
                }
            });

            // Close the sidebar if clicked outside the sidebar
            $(document).click(function(event) {
                if (!$(event.target).closest('#sidebar').length && !$(event.target).closest(
                        '#sidebarCollapse').length) {
                    if ($('#sidebar').hasClass('active')) {
                        $('#sidebar').removeClass('active'); // Close the sidebar
                        $('#sidebarCollapse').css('opacity', '1'); // Show the hamburger icon again
                    }
                }
            });

            // Prevent the sidebar from closing when clicking inside the sidebar
            $('#sidebar').click(function(event) {
                event.stopPropagation();
            });

            // Prevent the sidebar from closing when clicking the hamburger icon
            $('#sidebarCollapse').click(function(event) {
                event.stopPropagation();
            });
        });
    </script>


    <script>
        //Get all links inside the sidebar and header
        const links = document.querySelectorAll('.dynamic-link');
        // Target the content area
        const contentArea = document.querySelector('.content-area');
        // Declare once 
        // Add click event listeners to each link links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent the default action of the link
            const url = link.getAttribute('href'); // Get the URL of the link

            // Load content into the content area using Fetch API
            fetch(url)
                .then(response => {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw new Error('Failed to load content');
                    }
                })
                .then(data => {
                    contentArea.innerHTML = data; // Insert the content
                })
                .catch(err => {
                    console.error('Error loading content:', err);
                    contentArea.innerHTML =
                        `<p style="color: red;">Failed to load content. Please try again.</p>`;
                });
        });
    </script>
</body>

<style>
    .navbar {
        margin: 0;
        padding: 0;
    }

    /* Sidebar and Content styles */
    .wrapper {
        display: flex;
        width: 100%;
        position: relative;
    }

    .wrapper h2 {
        font-size: 38px;
        font-family: fantasy;
    }

    .wrapper p {
        font-size: 15px;
        font-family: monospace;
        text-align: center;
    }


    /* Content area */
    #content {
        transition: margin - left 0.3 s ease;
        padding-left: 15 px;
        width: 100%;
        margin-left: 0;
    }


    .content-area {
        flex: 1;
        padding: 20 px;
        background: transparent;
        overflow-y: auto;
    }

    .but {
        display: flex;
        justify-content: center;


    }

    .btn- primary {
        background-color: #FFC3FC !important;
        border-color: #FF94F7 !important;
        color: black !important;
        border: none;
        /* Remove border */
        padding: 10 px 20 px;
        /* Add some padding */
        cursor: pointer;
        /* Change cursor to pointer on hover */
        font-size: 16 px;
        /* Adjust font size */
        border-radius: 5 px;
        /* Add rounded corners */
        transition: 0.3 s;

    }

    .btn-primary:hover {
        background-color: #FF94F7;

    }
</style>

</html>