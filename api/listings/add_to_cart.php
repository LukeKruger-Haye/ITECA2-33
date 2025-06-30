<?php

include_once("../../includes/utils.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$listing_id = $_POST['listing_id'] ?? null;

if (in_array($listing_id, $_SESSION['cart'] ?? [])) {
    echo json_encode(['success' => false, 'message' => 'No listing_id provided.']);
    exit;
}

$_SESSION['cart'][] = $listing_id;

echo json_encode(['success' => true, 'message' => 'Added to cart.']);

?>