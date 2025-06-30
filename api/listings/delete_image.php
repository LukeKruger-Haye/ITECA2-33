<?php

include_once("../../includes/utils.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['media_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$pdo = connect_db();

$media_id = intval($_POST['media_id']);

$stmt = $pdo -> prepare("SELECT file_path FROM listing_media WHERE media_id = ?");
$stmt -> execute([$media_id]);
$image = $stmt -> fetch(PDO::FETCH_ASSOC);

if ($image) {
    unlink(dirname(__DIR__,2) . $image['file_path']);

    $pdo -> prepare("DELETE FROM listing_media WHERE media_id = ?")->execute([$media_id]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

?>