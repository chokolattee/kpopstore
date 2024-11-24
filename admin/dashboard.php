<?php
session_start();
include("../includes/headera.php");
include("../includes/config.php");
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

    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <br>
                <br>
                <h2>ADMIN ACCESS</h2>
            </div>
            <ul class="list-unstyled components">
                <p>what do you want to do today?</p>
                <br>

                <li><a href="create.php"> Admin Management</a></li>
                <li><a href="/kpopstore/admin/users.php">Users Management</a></li>
                <li><a href="/kpopstore/artists/index.php">Artist Management</a></li>
                <li><a href="/kpopstore/admin/orders.php">Order Management</a></li>
                <li><a href="/kpopstore/item/index.php">Item Management</a></li>
            </ul>
            <br>
            <div class="but">
                <button type="button" class="btn btn-primary" id="back-button">Back</button>
            </div>
        </nav>


        <div class="content-area">
            <p></p>
        </div>
    </div>

    <!-- Page Content -->
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="">
                <!-- Sidebar Toggle Button (Hamburger Icon) -->
                <button type="button" id="sidebarCollapse" class="btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
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
    // Get all links inside the sidebar
    const links = document.querySelectorAll('.components li a');

    // Target the content area
    const contentArea = document.querySelector('.content-area'); // Declare once

    // Add click event listeners to each link
    links.forEach(link => {
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
    });

    // Add event listener to the "Back" button
    const backButton = document.getElementById('back-button');
    backButton.addEventListener('click', function() {
        // Clear the content area
        contentArea.innerHTML = ''; // Use the existing `contentArea` variable
    });
    </script>



</body>

</html>

<style>
/* Sidebar and Content styles */
/* Remove any unwanted margin or padding from navbar */
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


#sidebar {
    width: 0;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    background-color: #d3a3e4;
    color: #fff;
    transition: width 0.3s ease;
    overflow-x: hidden;
}

/* Sidebar when open */
#sidebar.active {
    width: 250px;
}

/* Content area */
#content {
    transition: margin-left 0.3s ease;
    padding-left: 15px;
    width: 100%;
    margin-left: 0;
}

/* When sidebar is active, content shifts */
#sidebar.active+#content {
    margin-left: 250px;
}

/* Position and style for the sidebar toggle button */
#sidebarCollapse {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1000;
    font-size: 30px;
    background-color: #d3a3e4;
    border: none;
    padding: 10px;
    cursor: pointer;
    color: white;
    transition: opacity 0.3s ease;
}

/* Adjust button style for better visibility */
#sidebarCollapse:hover {
    background-color: #b0a0d6;
}

/* Hide the hamburger icon when the sidebar is active */
#sidebar.active+#content #sidebarCollapse {
    opacity: 0;
    pointer-events: none;
}

/* Hamburger icon styling */
#sidebarCollapse i {
    color: white;
    display: block;
}


ul.list-unstyled {
    gap: 20px;
    text-align: center;

}



ul.list-unstyled li {
    margin: 10px 0;
    list-style-type: none;

}


ul.list-unstyled li a {
    text-decoration: none;
    color: #880085;
    font-size: 20px;
    font-family: serif;
    transition: color 0.3s, transform 0.3s;

}

ul.list-unstyled li a:hover {
    color: #0056b3;
    transform: translateX(5px);

}

.content-area {
    flex: 1;
    padding: 20px;
    background: transparent;
    overflow-y: auto;
}

.but {
    display: flex;
    justify-content: center;


}

.btn-primary {
    background-color: #FFC3FC !important;
    border-color: #FF94F7 !important;
    color: black !important;
    border: none;
    /* Remove border */
    padding: 10px 20px;
    /* Add some padding */
    cursor: pointer;
    /* Change cursor to pointer on hover */
    font-size: 16px;
    /* Adjust font size */
    border-radius: 5px;
    /* Add rounded corners */
    transition: 0.3s;

}

.btn-primary:hover {
    background-color: #FF94F7;

}
</style>