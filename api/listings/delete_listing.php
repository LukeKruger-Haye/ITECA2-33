<?php

include_once("../../includes/utils.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please log in to delete a listing!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method!']);
    exit;
}

$listing_id = $_POST['listing_id'] ?? '';

if (empty($listing_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Listing ID is required!']);
    exit;
}

$pdo = connect_db();

try {
    $stmt = $pdo -> prepare("SELECT seller_id FROM listings WHERE listing_id = ?");
    $stmt -> execute([$listing_id]);
    $listing = $stmt -> fetch(PDO::FETCH_ASSOC);

    if (!$listing) {
        echo json_encode(['status' => 'error', 'message' => 'Listing not found!']);
        exit;
    }

    if ($listing['seller_id'] != $_SESSION['user_id']) {
        echo json_encode(['status' => 'error', 'message' => 'You do not have permission to delete this listing!']);
        exit;
    }

    $stmt = $pdo -> prepare("DELETE FROM listings WHERE listing_id = ?");
    $stmt -> execute([$listing_id]);

    $upload_dir = dirname(__DIR__, 2) . '/uploads/listings/' . $listing_id . '/';

    if (is_dir($upload_dir)) {
        array_map('unlink', glob("$upload_dir/*.*"));
        rmdir($upload_dir);
    }

    $stmt = $pdo -> prepare("DELETE FROM listing_media WHERE listing_id = ?");
    $stmt -> execute([$listing_id]);

    echo json_encode(['status' => 'success', 'message' => 'Listing deleted successfully!']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>