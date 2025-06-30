<?php

include_once("../../includes/utils.php");

$pdo = connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_id = $_POST['receiver_id'] ?? null;
    $message = $_POST['message'] ?? null;

    if ($receiver_id && $message) {
        $stmt = $pdo->prepare("
            INSERT INTO messages (sender_id, receiver_id, message, date_created)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$_SESSION['user_id'], $receiver_id, $message]);

        echo json_encode(['status' => 'success', 'message' => 'Message sent successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    }
}

?>