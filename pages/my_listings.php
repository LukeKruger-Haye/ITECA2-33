<?php
include_once("../includes/utils.php");
include_once("../includes/header/header.php");

$listings_per_page = 12;
$current_page_idx = isset($_GET['page_idx']) ? (int)$_GET['page_idx'] : 1;
$offset = ($current_page_idx - 1) * $listings_per_page;

$pdo = connect_db();

try {
    $count_stmt = $pdo -> prepare("SELECT COUNT(*) FROM listings WHERE seller_id = ?");
    $count_stmt -> execute([$_SESSION['user_id']]);

    $total_listings = $count_stmt -> fetchColumn();
    $total_page_idxs = ceil($total_listings / $listings_per_page);
    
    $stmt = $pdo -> prepare("
        SELECT l.*, u.first_name, 
               (SELECT file_path FROM listing_media WHERE listing_id = l.listing_id AND file_type = 'image' AND display_order = 1 LIMIT 1) as main_image
        FROM listings l 
        JOIN users u ON l.seller_id = u.user_id 
        WHERE l.seller_id = ?
        ORDER BY l.created_at DESC 
        LIMIT ? OFFSET ?
    ");
    $stmt -> bindValue(1, $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt -> bindValue(2, $listings_per_page, PDO::PARAM_INT);
    $stmt -> bindValue(3, $offset, PDO::PARAM_INT);
    $stmt -> execute();

    $listings = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    foreach ($listings as &$listing) {
        $listing_dir = dirname(__DIR__) . "/uploads/listings/" . $listing['listing_id'] . "/";
        $main_image = null;

        if (is_dir($listing_dir)) {
            $files = glob($listing_dir . "*.{jpg,jpeg,png,JPG,JPEG,PNG}", GLOB_BRACE);

            if (!empty($files)) {
                $main_image = str_replace(dirname(__DIR__), "..", $files[0]);
            }
        }

        $listing['main_image'] = $main_image;
    }
    
    unset($listing);
} catch (PDOException $e) {
    echo "Error: " . $e -> getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings</title>
    <link rel="stylesheet" href="styles/listings.css">
</head>
<body>
    <div class="listings-container">
        <br><br><br><br>
        <div class="listings-header">        
            <h1>My Listings</h1>
            <a href="../index.php?page=create_listing" id="redirect-create-listing">New listing</a>
        </div>
        
        <?php if (empty($listings)): ?>
            <div class="no-listings">
                <h3>No listings found</h3>
                <p>Create your first listing! <a href="../index.php?page=create_listing">Here!</a></p>
            </div>
        <?php else: ?>
            <div class="listings-grid">
                <?php foreach ($listings as $listing): ?>
                    <a href="current_listing.php?listing_id=<?php echo urlencode($listing['listing_id']); ?>" class="listing-link">

                    <div class="listing-card">
                        <?php if ($listing['main_image']): ?>
                            <img src="<?php echo htmlspecialchars($listing['main_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($listing['name']); ?>" 
                                 class="listing-image">
                        <?php else: ?>
                            <div class="listing-image" style="display: flex; align-items: center; justify-content: center; color: #999;">
                                No Image
                            </div>
                        <?php endif; ?>
                        
                        <div class="listing-info">
                            <div class="listing-name">
                                <?php echo htmlspecialchars($listing['name']); ?>
                            </div>
                            
                            <?php if (isset($listing['price'])): ?>
                                <div class="listing-price">
                                    R<?php echo number_format($listing['price'], 2); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="listing-seller">
                                by <?php echo htmlspecialchars($listing['first_name']); ?>
                            </div>
                            
                            <div class="listing-description">
                                <?php echo htmlspecialchars($listing['description']); ?>
                            </div>
                        </div>
                    </div>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <?php if ($total_page_idxs > 1): ?>
                <div class="pagination">
                    <?php if ($current_page_idx > 1): ?>
                        <a href="?page_idx=<?php echo $current_page_idx - 1; ?>">< Previous</a>
                    <?php else: ?>
                        <span class="disabled">< Previous</span>
                    <?php endif; ?>
                    
                    <?php 
                    $start_page_idx = max(1, $current_page_idx - 2);
                    $end_page_idx = min($total_page_idxs, $current_page_idx + 2);
                    
                    if ($start_page_idx > 1): ?>
                        <a href="?page_idx=1">1</a>
                        <?php if ($start_page_idx > 2): ?>
                            <span>...</span>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php for ($i = $start_page_idx; $i <= $end_page_idx; $i++): ?>
                        <?php if ($i == $current_page_idx): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page_idx=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($end_page_idx < $total_page_idxs): ?>
                        <?php if ($end_page_idx < $total_page_idxs - 1): ?>
                            <span>...</span>
                        <?php endif; ?>
                        <a href="?page_idx=<?php echo $total_page_idxs; ?>"><?php echo $total_page_idxs; ?></a>
                    <?php endif; ?>

                    <?php if ($current_page_idx < $total_page_idxs): ?>
                        <a href="?page_idx=<?php echo $current_page_idx + 1; ?>">Next ></a>
                    <?php else: ?>
                        <span class="disabled">Next ></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>

<?php 

include_once("../includes/footer/footer.php");

?>