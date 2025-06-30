<?php 

include_once("../../includes/utils.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['listing_id']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$pdo = connect_db();

$stmt = $pdo -> prepare("INSERT INTO reviews (listing_id, user_id, message, rating) VALUES (?, ?, ?, ?)");
$stmt -> execute([$_POST['listing_id'], $_SESSION['user_id'], $_POST['comment'], $_POST['rating']]);

?>