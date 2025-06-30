<?php

include_once("../includes/header/header.php");
include_once("../includes/utils.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles/checkout.css">
</head>
<body>
    <br><br><br><br><br><br>
    <div class="container">
        <div class="container-header">
            <h1>Checkout</h1>
            <p>Complete your purchase</p>
        </div>

        <div class="checkout-container">
            <div class="checkout-header">
                <h2>Order Summary</h2>
            </div>

            <div class="checkout-details">
                <?php
                $cart = $_SESSION['cart'] ?? [];
                $total_price = 0;

                if (empty($cart)) {
                    echo "<p>Your cart is empty. Please add items to your cart before checking out.</p>";
                } else {
                    foreach ($cart as $listing_id) {
                        $pdo = connect_db();
                        $stmt = $pdo->prepare("SELECT l.name, l.price, u.first_name, u.last_name 
                                               FROM listings l 
                                               JOIN users u ON l.seller_id = u.user_id 
                                               WHERE l.listing_id = ?");
                        $stmt->execute([$listing_id]);
                        $listing = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($listing) {
                            echo "<p>R{$listing['price']} - {$listing['name']} by {$listing['first_name']} {$listing['last_name']}</p>";
                            $total_price += $listing['price'];
                        }
                    }

                    echo "<h3>Total: R{$total_price}</h3>";
                }
                ?>
            </div>

            <a href="../index.php?page=payment_gateway">
                <button type="submit" class="checkout-button">Complete Purchase</button>    
            </a>

            <a href="../index.php?page=cart" id="back-to-cart">Go back to cart?</a>
        </div>
    </div>

    <br><br><br>
</body>
</html>

<?php

include_once("../includes/footer/footer.php");

?>