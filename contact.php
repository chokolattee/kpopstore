<?php
session_start();
include('./includes/header.php');
include('./includes/config.php');


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
</head>

<body style="background-color: #E6E0F4 ">
    <br>
    <br>
    <div class="contact-container" ; style="background-color: #D2C8EA ">
        <h2>Contact Us</h2>
        <!-- Developer Section -->
        <div class=" developers">
            <div class="developer">
                <img src="im1.png" alt="Developer 1">
                <h3>Mary Pauline Calungsod</h3>
            </div>
            <div class="developer">
                <img src="im2.png" alt="Developer 2">
                <h3>Kimberly G. Eledia</h3>
            </div>
        </div>
        <!-- Contact Details -->
        <div class="contact-item">
            <strong>Phone Number:</strong>
            <span><a href="09679171629"></a> 09679171629</span>
        </div>
        <div class=" contact-item">
            <strong>Email Address:</strong>
            <span><a href="hallyu@gmail.com">hallyu@gmail.com</a></span>
        </div>
        <div class="contact-item">
            <strong>Address:</strong>
            <span>Taguig City</span>
        </div>
        <div class="contact-item">
            <strong>Working Hours:</strong>
            <span>Monday - Sunday: 9:00 AM - 6:00 PM</span>
        </div>
    </div>
</body>

</html>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 20px;
    }

    .contact-container {
        max-width: 600px;
        margin: auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .contact-container h2 {
        color: #333;
        margin-bottom: 20px;
        font-size: 40px;
    }

    .contact-item {
        margin-bottom: 15px;
    }

    .contact-item strong {
        display: block;
        font-size: 18px;
        margin-bottom: 5px;
    }

    .contact-item span {
        font-size: 16px;
        color: #555;
    }

    .contact-item a {
        color: #5B148F;
        text-decoration: none;
    }

    .contact-item a:hover {
        text-decoration: underline;
    }

    .developers {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin: 20px 0;
    }

    .developer {
        text-align: center;
    }

    .developer img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .developer h3 {
        font-size: 16px;
        margin-top: 10px;
        color: #333;
    }
    </style>