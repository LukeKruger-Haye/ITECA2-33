<?php

include_once("../includes/utils.php");
include_once("../includes/header/header.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/create_listing.css">
    <title>Create Listing</title>
</head>
<body>
    <br><br><br><br><br><br><br><br>
    <div class="container">
        <div class="container-header">
            <h1>Create New Listing</h1>
            <p>Sell your goods and services to other South Africans</p>
        </div>

        <div class="form-container">
            <form action="" id="listing-form" enctype="multipart/form-data">
                <div class="form-input-group">
                    <label for="name">Listing Name</label>
                    <input type="text" id="name" name="name" class="form-input" required placeholder="Enter name">
                </div>

                <div class="form-input-group">
                    <label for="price">Listing price (R)</label>
                    <input type="number" id="price" name="price" class="form-input" required min="0" step="0.01" placeholder="0.00">
                </div>

                <div class="form-input-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" class="form-input" required placeholder="Describe your listing in detail..."></textarea>
                </div>

                <div class="form-input-group">
                    <label>Images (10 Maximum)</label>

                    <div class="form-upload-area" id="upload-area">
                        <div class="upload-text">Click to upload or drag and drop</div>
                        <div class="upload-hint">PNG, JPG, JPEG up to 5MB each</div>
                        <input type="file" id="images" name="images[]" class="file-input" multiple accept="image/*">
                    </div>

                    <div class="file-preview-container" id="file-preview"></div>
                </div>
            
                <button type="submit" class="form-submit-button" id="submit-button">Create Listing</button>
            </form>
        </div>
    </div>
    <br><br><br><br>

    <script src="js/create_listing.js"></script>
</body>
</html>

<?php

include_once("../includes/footer/footer.php");

?>