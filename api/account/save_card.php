<?php

include_once("../../includes/utils.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_number = $_POST['card_number'];
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];
    $cardholder_name = $_POST['card_name'];

    if (empty($card_number) || empty($expiry) || empty($cvv) || empty($cardholder_name)) {
        echo "Missing data";
        exit;
    }

    $card_number = htmlspecialchars($card_number);
    $expiry = htmlspecialchars($expiry);
    $cvv = htmlspecialchars($cvv);
    $cardholder_name = htmlspecialchars($cardholder_name);

    $key = get_key();

    $card_number = encrypt_string($card_number, $key);
    $expiry = encrypt_string($expiry, $key);
    $cvv = encrypt_string($cvv, $key);
    $cardholder_name = encrypt_string($cardholder_name, $key);

    $pdo = connect_db();

    try {
        $stmt = $pdo->prepare("SELECT card_id FROM cards WHERE user_id = ?");
        $stmt -> execute([$_SESSION['user_id']]);

        $exists = $stmt -> fetch();

        if ($exists) {
            $stmt = $pdo -> prepare("UPDATE cards SET card_number = ?, expiry = ?, cardholder_name = ?, cvv = ? 
            WHERE user_id = ?");
            $stmt -> execute([$card_number, $expiry, $cardholder_name, $cvv, $_SESSION['user_id']]);
        } else {
            $stmt = $pdo -> prepare("INSERT INTO cards (user_id, card_number, expiry, cardholder_name, cvv) 
            VALUES (?, ?, ?, ?, ?)");
            $stmt -> execute([$_SESSION['user_id'], $card_number, $expiry, $cardholder_name, $cvv]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Profile updated!']);
        redirect("../../pages/account_settings.php#billing");
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        redirect("../../pages/account_settings.php#billing");
    }
} else {
    echo "Invalid request";
    redirect("../../pages/account_settings.php#billing");
}

?>