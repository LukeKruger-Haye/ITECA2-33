<?php

include_once("../includes/utils.php");
include_once("../includes/header/header.php");

$listing_id = $_GET['listing_id'];

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = null;
}

$pdo = connect_db();

$stmt = $pdo -> prepare("SELECT * FROM listings WHERE listing_id = ?");
$stmt -> execute([$listing_id]);
$listing = $stmt -> fetch(PDO::FETCH_ASSOC);

$stmt = $pdo -> prepare("SELECT file_path FROM listing_media WHERE listing_id = ? AND file_type = 'image' ORDER BY display_order");
$stmt -> execute([$listing_id]);
$images = $stmt -> fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo -> prepare("SELECT u.user_id, u.first_name, u.last_name FROM users u JOIN listings l ON u.user_id = l.seller_id WHERE l.listing_id = ?");
$stmt -> execute([$listing_id]);
$seller = $stmt -> fetch(PDO::FETCH_ASSOC);

$stmt = $pdo -> prepare("SELECT * FROM reviews WHERE listing_id = ?");
$stmt -> execute([$listing_id]);
$reviews = $stmt -> fetchAll(PDO::FETCH_ASSOC);

$average_rating = 0;

if (count($reviews) > 0) {
    $total_rating = 0;

    foreach ($reviews as $review) {
        $total_rating += $review['rating'];
    }

    $average_rating = $total_rating / count($reviews);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo urlencode($listing['name']);?></title>
    <link rel="stylesheet" href="styles/current_listing.css">
</head>
<body>
    <br><br><br><br><br>

    <div class="container">
        <div class="image-container">
            <?php if (!empty($images)):  ?>
                <img id="listing-image" src="<?php echo '..' . htmlspecialchars($images[0]['file_path']); ?>" alt="<?php echo urlencode($listing['name']); ?>">

                <div class="image-buttons">
                    <button class="image-button" id="previous-button"><</button>
                    <button class="image-button" id="next-button">></button>
                </div>
            <?php else: ?>
                <p>No images available for this listing.</p>
            <?php endif; ?>
        </div>
        
        <div class="details-container">
            <h1><?php echo htmlspecialchars($listing['name']); ?></h1>
            <p>By <?php echo htmlspecialchars($seller['first_name']) . ' ' . htmlspecialchars($seller['last_name']); ?> | 
                <a href="../pages/messages.php?chat_user=<?php echo urlencode($seller['user_id']); ?>">Send Message</a>
            </p>
            <p id="listing-description"> <?php echo nl2br(htmlspecialchars($listing['description'])); ?></p>
        </div>

        <div class="price-container">
            <h2>R<?php echo number_format($listing['price'], 2); ?></h2>
            <button onclick="add_to_cart(<?php echo htmlspecialchars($listing_id); ?>)">Add to cart</button>

            <?php if ($average_rating > 0): ?>
                <h4 id="rating">⭐ <?php echo $average_rating?> Stars</h4>
            <?php else:?>
                <h4 id="rating">No ratings yet</h4>
            <?php endif; ?>
        </div>

        <?php if ($listing['seller_id'] == $_SESSION['user_id']): ?>
            <div class="edit-container">
                <a href="edit_listing.php?listing_id=<?php echo urldecode($listing_id)?>"><button id="edit-button">Edit Listing</button></a>
                <button id="delete-button" onclick="delete_listing(<?php echo htmlspecialchars($listing_id)?>)">Delete Listing</button>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="reviews-container">
        <h2 id="reviews-header">Reviews</h2>
        <?php if (count($reviews) > 0): ?>
            <h3 class="reviews-average-rating">Average Rating: <?php echo number_format($average_rating, 1); ?> stars</h3>

            <?php if (isset($_SESSION['user_id'])): ?>
            <h4 class="reviews-leave-review">Leave a review:</h4>
            <form action="../api/listings/add_review.php" method="POST">
                <input type="hidden" name="listing_id" value="<?php echo htmlspecialchars($listing_id); ?>">

                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" required></textarea>

                <label for="rating">Rating:</label>
                <select id="rating" name="rating" required>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>

                <button type="submit">Submit Review</button>
            </form>
            <?php else: ?>
                <div class="reviews-not-logged-in">
                    <p>You must be logged in to leave a review.</p>
                    <a href="../index.php?page=login">Login</a> or <a href="../index.php?page=register">Register</a>
                </div>
            <?php endif; ?>

            <ul>
                <?php foreach ($reviews as $review): ?>
                    <?php
                        $pdo = connect_db();

                        $stmt = $pdo -> prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");
                        $stmt -> execute([$review['user_id']]);
                        $reviewer = $stmt -> fetch(PDO::FETCH_ASSOC);

                        $reviewer_name = $reviewer['first_name'] . ' ' . $reviewer['last_name'];
                    ?>

                    <li>
                        <div class="reviews-review-header">
                            <strong><?php echo htmlspecialchars($reviewer_name) ?></strong>:
                            <span class="rating"><?php echo $review['rating']; ?> Stars</span>
                        </div>
                        <span><?php echo htmlspecialchars($review['message']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <h3 class="reviews-average-rating">No reviews yet.</h3>

            <?php if (isset($_SESSION['user_id'])): ?>
            <h4 class="reviews-leave-review">Leave a review:</h4>
            <form action="../api/listings/add_review.php" method="POST">
                <input type="hidden" name="listing_id" value="<?php echo htmlspecialchars($listing_id); ?>">

                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" required></textarea>

                <label for="rating">Rating:</label>
                <select id="rating" name="rating" required>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>

                <button type="submit">Submit Review</button>
            </form>
            <?php else: ?>
                <p>You must be logged in to leave a review.</p>
                <a href="../index.php?page=login">Login</a> or <a href="../index.php?page=register">Register</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        const images = <?php echo json_encode(array_column($images, 'file_path')); ?>;
        let current = 0;
        const img = document.getElementById('listing-image');

        document.getElementById('previous-button').onclick = function () {
            current = (current - 1 + images.length) % images.length;
            img.src = ".." + images[current];
        };

        document.getElementById('next-button').onclick = function () {
            current = (current + 1) % images.length;
            img.src = ".." + images[current];
        };

        function add_to_cart(listing_id) {
            const params = new URLSearchParams();

            params.append('listing_id', listing_id);

            fetch('../api/listings/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Item added to cart successfully!');
                } else {
                    alert('Failed to add item to cart: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the item to the cart.');
            });
        }

        function delete_listing(listing_id) {
            if (confirm("Are you sure you want to delete this listing? This action cannot be undone.")) {
                const params = new URLSearchParams();
                params.append('listing_id', listing_id);

                fetch('../api/listings/delete_listing.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: params,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.href = '../index.php?page=listings';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the listing.');
                });
            }
        }

    </script>
</body>
</html>

<?php

include_once("../includes/footer/footer.php");

?>