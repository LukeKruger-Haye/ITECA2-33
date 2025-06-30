<?php
    include_once("../includes/utils.php");
    include_once("../includes/header/header.php");

    $image_dir = __DIR__ . "/assets/home/";
    $image_files = glob($image_dir . "*.{jpg,jpeg,png,JPG,JPEG,PNG}", GLOB_BRACE);

    shuffle($image_files);

    $selected_images = array_slice($image_files, 0, 5);

    $web_images = array_map(function($path) {
        return "assets/home/" . basename($path);
    }, $selected_images);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/home.css">
    <title>Home</title>
</head>
<body>
    <br><br><br><br><br>

    <h1 id="welcome-header">Welcome to South Africa's newest marketplace</h1>
    <p id="message">Sell your products or buy your dream products</p>

    <br><br>

    <div class="home-gallery">
        <img id="gallery-image" src="<?php echo htmlspecialchars($web_images[0] ?? ''); ?>" alt="Gallery Image">

        <button type="button" id="shop-now-button" onclick="location.href='../index.php?page=listings'">Shop now!</button>

        <div class="gallery-dots">
            <?php for ($i = 0; $i < count($web_images); $i++): ?>
                <span class="gallery-dot<?php if ($i === 0) echo ' active'; ?>" data-index="<?php echo $i; ?>"></span>
            <?php endfor; ?>
        </div>
    </div>

    <script>
        const images = <?php echo json_encode($web_images); ?>;
    </script>
    <script src="js/home.js"></script>
</body>
</html>

<?php
    include_once("../includes/footer/footer.php");
?>