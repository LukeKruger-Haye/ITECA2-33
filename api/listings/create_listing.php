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

$name = trim($_POST['name'] ?? '');
$price = $_POST['price'] ?? '';
$description = trim($_POST['description'] ?? '');

if (empty($name) || empty($price) || empty($description)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields!']);
    exit;
}

if (!is_numeric($price) || $price < 0) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid price!']);
    exit;
}

if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
    echo json_encode(['status' => 'error', 'message' => 'Please upload at least one image!']);
    exit;
}

$name = htmlspecialchars($name);
$description = htmlspecialchars($description);
$price = floatval($price);
$seller_id = $_SESSION['user_id'];

$upload_dir = "/uploads/listings/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$pdo = connect_db();

try {
    $pdo -> beginTransaction();

    $stmt = $pdo -> prepare("INSERT INTO listings (seller_id, name, description, price) VALUES (?, ?, ?, ?)");
    $stmt -> execute([$seller_id, $name, $description, $price]);

    $listing_id = $pdo -> lastInsertId();

    $upload_dir = dirname(__DIR__, 2) . '/uploads/listings/' . $listing_id . '/';

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $uploaded_images = [];
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $max_size = 5 * 1024 * 1024; 
    $max_files = 10;
    
    $file_count = count($_FILES['images']['name']);
    
    if ($file_count > $max_files) {
        throw new Exception("Maximum $max_files images allowed!");
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
            throw new Exception("Error uploading $file_name!");
        }
        
        if (!in_array($file_type, $allowed_types)) {
            throw new Exception("$file_name is not a valid image type!");
        }
        
        if ($file_size > $max_size) {
            throw new Exception("$file_name is too large! Maximum 5MB per image.");
        }
        
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_name = uniqid() . '_' . time() . '.' . $file_extension;
        $file_path = $upload_dir . $unique_name;
        
        if (!move_uploaded_file($file_tmp, $file_path)) {
            throw new Exception("Failed to save $file_name!");
        }

        $web_file_path = "/uploads/listings/" . $listing_id . '/' . $unique_name;

        $media_stmt = $pdo -> prepare("INSERT INTO listing_media (listing_id, file_path, file_type, display_order) VALUES (?, ?, 'image', ?)");
        $media_stmt -> execute([$listing_id, $web_file_path, $i + 1]);

        $uploaded_images[] = $file_path;
    }
    
    if (empty($uploaded_images)) {
        throw new Exception("No images were successfully uploaded!");
    }
    
    $pdo -> commit();
    
    echo json_encode([
        'status' => 'success', 
        'message' => 'Listing created successfully!',
        'listing_id' => $listing_id,
        'images_uploaded' => count($uploaded_images)
    ]);
    
} catch (Exception $e) {
    $pdo -> rollback();
    
    if (!empty($uploaded_images)) {
        foreach ($uploaded_images as $image_path) {
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
    }
    
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>