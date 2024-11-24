<?php session_start();
 include('./includes/header.php'); 
include('./includes/config.php');
include('./includes/alert.php'); 

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message']='Please log in to access resources' ; 
    header("Location: /kpopstore/user/login.php"); 
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT r.role_id FROM user u INNER JOIN role r ON u.role_id = r.role_id WHERE u.user_id = '$user_id' AND r.role_id = 2 LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['message'] = 'You must be logged in as user to access this page.';
    header("Location: /kpopstore/user/login.php");
}

$sql = "SELECT CONCAT(u.fname, ' ', u.lname) AS full_name, u.address, u.contact_number
        FROM user u
        WHERE u.user_id = $user_id LIMIT 1";

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $customerName = $user['full_name'];
    $address = $user['address'];
    $contact = $user['contact_number'];
}

$regions = [];
$sql = "SELECT region, rate, shipping_id FROM shipping";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $regions[] = $row;
        }
    }

    $selectedRegion = isset($_POST['region']) ? $_POST['region'] : '';
    $shippingRate = 0;
    $total = isset($total) ? $total : 0;
    
    if ($selectedRegion) {
        foreach ($regions as $region) {
            if ($region['region'] === $selectedRegion) {
                $_SESSION['region'] = $region['region'];
                $_SESSION['shipping_rate'] = $region['rate'];
                $_SESSION['shipping_id'] = $region['shipping_id'];
                $shippingRate = $region['rate'];
                break;
            }
}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body style="background-color:#DFD2F4">
    <br>
    <h1 style="font-family: Carelia; font-size: 45px; text-align: center;">
        <strong> Shopping Cart </strong>
    </h1>


    <div class=" shopping-cart-container"
        style="display: flex; justify-content: flex-start; gap: 10px; padding: 20px; max-width: 1300px; margin: 0 auto;">
        <!-- Cart Items Section -->
        <div class="cart-items"
            style="flex: 2; justify-content: center; background:  #DFB9EE  ; padding: 15px; border-radius: 10px; color:black ;  box-shadow: 0 8px 10px rgba(0.2,0,1,0.1); ">
            <h3 style="text-align: center; ">
                You have <?= isset($_SESSION["cart_products"]) ? count($_SESSION["cart_products"]) : '0'; ?> items
                in your cart

            </h3>

            <form action=" qty_update.php" method="POST">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <br>
                        <tr>
                            <th style="padding: 10px 5px;">Item <br>Image</th>
                            <th style="text-align: center; padding: 10px 5px;">Item</th>
                            <th style="padding: 10px 5px;">Quantity</th>
                            <th style="padding: 10px 5px;">Price</th>
                            <th style="padding: 10px 5px;">Total</th>
                            <th style="padding: 10px 5px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_SESSION["cart_products"]) && count($_SESSION['cart_products']) > 0) {
                            $total = 0;
                            foreach ($_SESSION["cart_products"] as $cart_item) {
                                $imgPath = $cart_item['item_img'];
                                $product_name = $cart_item["item_name"];
                                $product_qty = $cart_item["item_qty"];
                                $product_price = $cart_item["item_price"];
                                $product_code = $cart_item["item_id"];
                                $subtotal = ($product_price * $product_qty);
                                $total += $subtotal;
                        ?>
                        <tr>
                            <td style="padding: 10px 0;">
                                <img src="<?= $cart_item['item_img']; ?>" alt="<?= $product_name; ?>"
                                    style="width: 80px; height: auto;" />
                            </td>
                            <td style="text-align: center; padding-left: 10px;"><?= $product_name; ?></td>
                            <td style="text-align: center;">
                                <input type="number" class="form-control" name="product_qty[<?= $product_code; ?>]"
                                    value="<?= $product_qty; ?>" min="1" style="width: 60px;" />
                            </td>
                            <td style="text-align: center;">₱<?= $product_price; ?></td>
                            <td style="text-align: center;">₱<?= $subtotal; ?></td>
                            <td style="text-align: center;">
                                <a href="delete_cart.php?id=<?= $cart_item['item_id']; ?>"
                                    style="color: red; text-decoration: none; margin-left: 10px;">
                                    <i class="fa-solid fa-trash" style="font-size: 16px;"></i> Remove
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3" style="text-align: right; padding-top: 10px;"><br>Subtotal:</td>
                            <td colspan="2" style="text-align:left; padding-top: 10px;">
                                <br> ₱<?= $total; ?>
                            </td>
                        </tr>
                        <?php } else { ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">Your cart is empty.</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div
                    style="display: flex; justify-content: center; align-items: center; width: 100%; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary"
                        style="width: 100%; font-family: sans; font-size: 17px; text-align: center;">
                        Update Cart
                    </button>
                </div>

            </form>
        </div>
        <br> <br> <br>
        <br> <br>

        <!-- Customer Info and Region Selection -->
        <div class="card-details">
            <div class="card-details"
                style="flex: 1; background:#FAE6FA; color: black; padding: 20px; border-radius: 10px; width: 600px;">

                <h3 style="text-align:center;">Customer Information</h3>
                <br>
                <form method="POST" action="">
                    <div style="margin-bottom: 10px;">
                        <label for="customer">Customer Name</label>
                        <input type="text" id="customer" name="customer" value="<?= ($customerName); ?>"
                            style="width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: none;"
                            readonly />
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="<?= ($address); ?>"
                            style="width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: none;"
                            readonly />
                    </div>
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <div>
                            <label for="contact">Contact</label>
                            <input type="text" id="contact" name="contact" value="<?= ($contact); ?>"
                                style="width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: none;"
                                readonly />
                        </div>
                        <div>
                            <label for="region">Region</label>
                            <select id="region" name="region"
                                style="width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: none;">
                                <option value="">Select Region</option>
                                <?php foreach ($regions as $region) { ?>
                                <option value="<?= ($region['region']); ?>"
                                    <?= ($selectedRegion == $region['region']) ? 'selected' : ''; ?>>
                                    <?= ($region['region']); ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <p>Subtotal: ₱<?= $total; ?></p>
                        <p id="shipping">Shipping: ₱<?= $shippingRate ?></p>
                        <p id="total">Total: ₱<?= $total + $shippingRate; ?></p>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; background-color:#F2C1D1 ; color:
                    black;">Confirm Details</button>
                    <br>
                    <br>
                    <a href="checkout.php" class="btn btn-primary"
                        style="width: 100%; padding: 10px; border-radius: 5px; background: #E0B0FF; border: none; color: black; text-align: center; display: inline-block; text-decoration: none;">
                        Checkout
                    </a>
                    <br>
                    <br>

                </form>
            </div>
        </div>

</body>

</html>