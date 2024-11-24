<?php
include("../includes/config.php");
include("../includes/header.php");

// Fetch data from the database
$query = "SELECT * FROM artists";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artists</title>
    <style>
    /* Add basic styling for the card layout */
    .card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        padding: 20px;
    }

    .card {

        background-color: #E5D4FF;
        border: 0.5px solid #E5CCFF;
        border-radius: 10px;
        margin: 10px;
        padding: 15px;
        text-align: center;
        width: 200px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        /* Align content vertically */
        justify-content: space-between;
        /* Ensure even spacing */
        transition: transform 0.3s, box-shadow 0.3s;

    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    .card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-title {
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <br>
    <H1 style="text-align:center; font-size:40px;">ARTISTS</H1>
    <div class="card-container">
        <?php
        // Loop through the fetched data and display it in cards
        while ($row = mysqli_fetch_assoc($result)) {
            
            echo '<div class="card">';
            
            echo '<img src="' . htmlspecialchars($row['img_path']) . '" alt="' . htmlspecialchars($row['artist_name']) . '">';
            echo '<div class="card-title">' . htmlspecialchars($row['artist_name']) . '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>

</html>

<?php
include("../includes/footer.php");
?>