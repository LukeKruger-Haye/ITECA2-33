<?php

include_once("../../includes/utils.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$listing_id = $_POST['listing_id'] ?? null;

if (in_array($listing_id, $_SESSION['cart'] ?? [])) {
    $key = array_search($listing_id, $_SESSION['cart']);

    if (false !== $key) {
        unset($_SESSION['cart'][$key]);
    }
}

echo json_encode(['success' => true, 'message' => 'Removed from cart.']);
?>