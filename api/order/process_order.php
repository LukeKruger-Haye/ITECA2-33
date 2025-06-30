<?php

include_once("../../includes/utils.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_number = $_POST['card_number'] ?? '';
    $cardholder_name = $_POST['cardholder_name'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    if (empty($card_number) || empty($cardholder_name) || empty($expiry_date) || empty($cvv)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    if (!preg_match('/^\d{16}$/', $card_number)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid card number.']);
        exit;
    }

    if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry_date)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid expiry date format. Use MM/YY.']);
        exit;
    }

    if (!preg_match('/^\d{3}$/', $cvv)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid CVV.']);
        exit;
    }

    $pdo = connect_db();
    $cart = $_SESSION['cart'];
    $total_price = 0;
    $listings = [];

    foreach ($cart as $listing_id) {
        $stmt = $pdo -> prepare("SELECT price FROM listings WHERE listing_id = ?");
        $stmt -> execute([$listing_id]);
        $listing = $stmt -> fetch(PDO::FETCH_ASSOC);

        $listings[] = $listing_id;
        $total_price += $listing['price'];
    }

    $stmt = $pdo -> prepare("INSERT INTO orders (user_id, total_price, listings) VALUES (?, ?, ?)");
    $stmt -> execute([$_SESSION['user_id'], $total_price, json_encode($listings)]);

    $_SESSION['cart'] = [];

    echo json_encode(['status' => 'success', 'message' => 'Payment processed successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

?>