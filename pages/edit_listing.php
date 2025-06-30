<?php 

include_once("../includes/header/header.php");
include_once("../includes/utils.php");

$pdo = connect_db();

$listing_id = $_GET['listing_id'] ?? null;

$stmt = $pdo -> prepare("SELECT * FROM listings WHERE listing_id = ? AND seller_id = ?");
$stmt -> execute([$listing_id, $_SESSION['user_id']]);

if ($stmt -> rowCount() === 0) {
    redirect("../index.php?page=home");
}

$listing = $stmt -> fetch(PDO::FETCH_ASSOC);

$stmt = $pdo -> prepare("SELECT media_id, file_path FROM listing_media WHERE listing_id = ? AND file_type = 'image' ORDER BY display_order");
$stmt -> execute([$listing_id]);
$listing_images = $stmt -> fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/edit_listing.css">
    <title>Edit Listing</title>
</head>
<body>
    <br><br><br><br><br><br><br><br>

    <div class="container">
        <div class="container-header">
            <h1>Edit Listing</h1>
            <p>Update your listing details</p>
        </div>

        <form action="../api/listings/edit_listing.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="listing_id" value="<?php echo $listing['listing_id']; ?>">

            <label for="name">Listing Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($listing['name']); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($listing['description']); ?></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?php echo $listing['price']; ?>" required>

            <div class="form-input-group">
                <label>Images (10 Maximum)</label>

                <div class="form-upload-area" id="upload-area">
                    <div class="upload-text">Click to upload or drag and drop</div>
                    <div class="upload-hint">PNG, JPG, JPEG up to 5MB each</div>
                    <input type="file" id="images" name="images[]" class="file-input" multiple accept="image/*">
                </div>

                <div class="file-preview-container" id="file-preview">
                    <?php foreach ($listing_images as $img): ?>
                        <div class="image-preview" data-media-id="<?php echo $img['media_id']; ?>">
                            <img src="..<?php echo htmlspecialchars($img['file_path']); ?>" alt="Listing Image">
                            <button type="button" class="remove-image" onclick="delete_image(<?php echo $img['media_id']; ?>, this)">x</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit">Update Listing</button>
        </form>

        <a href="../index.php?page=my_listings">Back to My Listings</a>
    </div>

    <br><br>

    <script src="js/edit_listing.js"></script>
</body>
</html>

<?php 

include_once("../includes/footer/footer.php");

?>