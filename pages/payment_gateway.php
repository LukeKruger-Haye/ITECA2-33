<?php

include_once("../includes/header/header.php");
include_once("../includes/utils.php");

$pdo = connect_db();
$key = get_key();

$stmt = $pdo -> prepare("SELECT card_number, expiry, cvv, cardholder_name FROM cards WHERE user_id = ?"); 
$stmt -> execute([$_SESSION['user_id']]);
$card = $stmt -> fetch();

if ($card) {
    $card['card_number'] = decrypt_string($card['card_number'], $key);
    $card['expiry'] = decrypt_string($card['expiry'], $key);
    $card['cvv'] = decrypt_string($card['cvv'], $key);
    $card['cardholder_name'] = decrypt_string($card['cardholder_name'], $key);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="styles/payment_gateway.css">
</head>
<body>
    <br><br><br><br><br><br>

    <div class="container">
        <div class="container-header">
            <h1>Payment Gateway</h1>
            <p>Complete your payment</p>
        </div>

        <div class="payment-container">
            <h2>Payment Details</h2>
            <form id="payment-form" action="../api/order/process_order.php" method="POST">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" required value="<?php if ($card != null) echo $card['card_number'];?>">

                <label for="cardholder_name">Cardholder Name:</label>
                <input type="text" id="cardholder_name" name="cardholder_name" required value="<?php if ($card != null) echo $card['cardholder_name'];?>">

                <label for="expiry_date">Expiry Date (MM/YY):</label>
                <input type="text" id="expiry_date" name="expiry_date" required value="<?php if ($card != null) echo $card['expiry'];?>">

                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required value="<?php if ($card != null) echo $card['cvv'];?>">

                <button type="submit">Pay Now</button>
            </form>
        </div>
    </div>

    <br><br><br><br>

    <script src="js/payment_gateway.js"></script>
</body>
</html>

<?php 

include_once("../includes/footer/footer.php");

?>