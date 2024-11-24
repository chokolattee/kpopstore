<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-Pop Store</title>
    <link rel="stylesheet" href='/kpopstore/includes/style/style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="style2.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
</head>

<body>
    <header class="header_ad">
        <div class="auth-buttons">
            <button onclick="window.location.href='/kpopstore/index.php'">Home Page</button>
            <button type="button" class="btn btn-lg btn-danger" data-bs-toggle="popover" data-bs-html="true"
                data-bs-title="Confirmation" data-bs-content='
                <p>Are you sure you want to log out?</p>
                <a href="/kpopstore/user/logout.php" class="btn btn-primary">Yes</a>
                <button class="btn btn-secondary cancel-popover">Cancel</button>'>
                Log Out
            </button>
            <button class="profile-button" onclick="window.location.href='/kpopstore/user/profile.php'">
                <i class="fas fa-user"></i>
            </button>
        </div>
        <br>
        <h1>
            <strong> WELCOME ADMIN</strong>
        </h1>
        <h3>관리자 환영</h3>

    </header>

    <script>
    // Initialize all popovers
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));

    // Close popover on "Cancel"
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('cancel-popover')) {
            const popoverElement = event.target.closest('.popover');
            if (popoverElement) {
                bootstrap.Popover.getInstance(popoverElement.parentElement).hide();
            }
        }
    });
    </script>




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
    <!-- jQuery Custom Scroller CDN -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
    </script>
</body>

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    background-color: #c4a7e1;
    color: purple;
    font-family: 'BOOKMAN OLD STYLE', sans-serif;
    margin: 0;
}

.header_ad {


    background-color: #D4A5FF;
    color: #FFF;
    padding: 20px;
    text-align: center;
    border-bottom: 2px solid #D4A5FF;
}

.header_ad h1 {
    font-size: 70px;
    color: white;
    margin-bottom: 10px;
}

.header_ad h3 {
    font-size: 40px;
    color: darkmagenta;
    margin-bottom: 10px;
}

.btn-danger.dropdown-toggle {
    background-color: blueviolet;
    color: white;
    border: none;
    padding: 10px;
    display: flex;
    justify-content: left;
}

.btn-danger.dropdown-toggle:hover {
    background-color: cornflowerblue;
}
</style>

</html>