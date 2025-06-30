<?php

include_once("../includes/utils.php");
include_once("../includes/header/header.php");

$cart = $_SESSION['cart'];

$total_price = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="styles/cart.css">
</head>
<body>
    <br><br><br><br><br>

    <div class="cart-header">
        <h1>Your Cart</h1>
    </div>

    <div class="container">
        <?php if (empty($cart)): ?>
            <p>Your cart is empty. <a href="../index.php?page=listings">Browse listings</a> to add items.</p>
        <?php else: ?>
            <div class="listings-container">
            <?php foreach ($cart as $listing_id): ?>
                <?php

                $pdo = connect_db();

                $stmt = $pdo -> prepare("SELECT l.listing_id, l.name, l.description, l.price, lm.file_path 
                                       FROM listings l 
                                       JOIN listing_media lm ON l.listing_id = lm.listing_id 
                                       WHERE l.listing_id = ? AND lm.file_type = 'image' 
                                       ORDER BY lm.display_order LIMIT 1");

                $stmt -> execute([$listing_id]);
                $listing = $stmt -> fetch(PDO::FETCH_ASSOC);

                $stmt = $pdo -> prepare("SELECT u.first_name, u.last_name 
                                        FROM users u 
                                        JOIN listings l 
                                        ON u.user_id = l.seller_id 
                                        WHERE l.listing_id = ?");

                $stmt -> execute([$listing_id]);
                $seller = $stmt -> fetch(PDO::FETCH_ASSOC);

                $total_price += $listing['price'];

                ?>

                <div class="listing-container">
                    <img src="<?php echo ".." . $listing['file_path'];?>" alt="<?php echo $listing['name'];?>" id="listing-image">

                    <div class="info-container">
                        <h3 id="listing-name"><?php echo $listing['name'];?></h3>
                        <p id="seller-info">From <?php echo $seller['first_name'] . ' ' . $seller['last_name'];?></p>
                    </div>
                    
                    <div class="price-container">
                        <h2 id="listing-price">R<?php echo $listing['price'];?></h2>
                        <button onclick="remove_from_cart(<?php echo $listing_id; ?>)">Remove from cart</button>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>

            <div class="checkout-container">
                <h2 id="total-price">Total: R<?php echo $total_price;?></h2>
                <a href="../index.php?page=checkout">
                    <button id="checkout-button">Checkout</button>
                </a>
                <a href="../index.php?page=listings">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <br><br>

    <script>
        function remove_from_cart(listing_id) {
            const params = new URLSearchParams();
            params.append('listing_id', listing_id);

            fetch('../api/listings/remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: params, 
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Item removed from cart successfully!');
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

<?php

include_once("../includes/footer/footer.php");

?>