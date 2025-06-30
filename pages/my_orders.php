<?php

include_once("../includes/header/header.php");
include_once("../includes/utils.php");

$pdo = connect_db();

$stmt = $pdo -> prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt -> execute([$_SESSION['user_id']]);
$orders = $stmt -> fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="styles/my_orders.css">
</head>
<body>
    <br><br><br><br><br><br><br>

    <div class="container">
    <?php if ($orders):  ?>
        <?php $count = 1; ?>

        <h1 id="container-header">My Orders</h1>
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Listings</th>
                    <th>Date</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($count++)?></td>
                        <td>
                            <?php
                            $listings = json_decode($order['listings'], true);

                            if ($listings && is_array($listings)) {
                                foreach ($listings as $listing_id) {
                                    $stmt = $pdo -> prepare("SELECT name FROM listings WHERE listing_id = ?");
                                    $stmt -> execute([$listing_id]);
                                    $listing_details = $stmt -> fetch(PDO::FETCH_ASSOC);

                                    echo htmlspecialchars($listing_details['name']) . "<br>";
                                }
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td>R<?php echo htmlspecialchars($order['total_price']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php endif ?>
    </div>
</body>
</html>

<?php

include_once("../includes/footer/footer.php");

?>