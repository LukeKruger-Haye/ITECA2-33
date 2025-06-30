<?php

include_once("../../includes/utils.php");


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please log in to create a listing!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method!']);
    exit;
}

$listing_id = $_POST['listing_id'] ?? null;
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = $_POST['price'] ?? null;

if (!$listing_id || !$name || !$description || !$price) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required!']);
    exit;
}

$pdo = connect_db();

$stmt = $pdo -> prepare("SELECT * FROM listings WHERE listing_id = ? AND seller_id = ?");
$stmt -> execute([$listing_id, $_SESSION['user_id']]);

if ($stmt -> rowCount() === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Listing not found']);
    exit;
}

$stmt = $pdo -> prepare("UPDATE listings SET name = ?, description = ?, price = ? WHERE listing_id = ? AND seller_id = ?");
$success = $stmt -> execute([$name, $description, $price, $listing_id, $_SESSION['user_id']]);

if (!$success) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update listing.']);
}

if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
    $upload_dir = dirname(__DIR__, 2) . '/uploads/listings/' . $listing_id . '/';

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $max_size = 5 * 1024 * 1024;
    $max_files = 10;

    $stmt = $pdo -> prepare("SELECT COUNT(*) FROM listing_media WHERE listing_id = ? AND file_type = 'image'");
    $stmt -> execute([$listing_id]);
    $existing_count = $stmt->fetchColumn();

    $file_count = count($_FILES['images']['name']);

    if ($existing_count + $file_count > $max_files) {
        echo json_encode(['status' => 'error', 'message' => "Maximum $max_files images allowed per listing!"]);
        exit;
    }

    $stmt = $pdo -> prepare("SELECT MAX(display_order) FROM listing_media WHERE listing_id = ? AND file_type = 'image'");
    $stmt -> execute([$listing_id]);
    $display_order = $stmt->fetchColumn();

    if (!$display_order) {
        $display_order = 0;
    }

    for ($i = 0; $i < $file_count; $i++) {
        $file_name = $_FILES['images']['name'][$i];
        $file_tmp = $_FILES['images']['tmp_name'][$i];
        $file_size = $_FILES['images']['size'][$i];
        $file_type = $_FILES['images']['type'][$i];
        $file_error = $_FILES['images']['error'][$i];

        if ($file_error === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        if ($file_error !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => 'error', 'message' => "Error uploading $file_name!"]);
            exit;
        }

        if (!in_array($file_type, $allowed_types)) {
            echo json_encode(['status' => 'error', 'message' => "$file_name is not a valid image type!"]);
            exit;
        }

        if ($file_size > $max_size) {
            echo json_encode(['status' => 'error', 'message' => "$file_name is too large! Maximum 5MB per image."]);
            exit;
        }

        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_name = uniqid() . '_' . time() . '.' . $file_extension;
        $file_path = $upload_dir . $unique_name;

        if (!move_uploaded_file($file_tmp, $file_path)) {
            echo json_encode(['status' => 'error', 'message' => "Failed to save $file_name!"]);
            exit;
        }

        $web_file_path = "/uploads/listings/" . $listing_id . '/' . $unique_name;
        $display_order++;

        $media_stmt = $pdo -> prepare("INSERT INTO listing_media (listing_id, file_path, file_type, display_order) VALUES (?, ?, 'image', ?)");
        $media_stmt -> execute([$listing_id, $web_file_path, $display_order]);
    }
}

echo json_encode(['status' => 'success', 'message' => 'Listing updated successfully!']);

?>