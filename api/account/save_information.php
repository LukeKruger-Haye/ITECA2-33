<?php

include_once("../../includes/utils.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone)) {
        echo "Missing data";
        exit;
    }

    $first_name = htmlspecialchars($first_name);
    $last_name = htmlspecialchars($last_name);
    $phone = htmlspecialchars($phone);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $pdo = connect_db();

    try {
        $stmt = $pdo -> prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE user_id = ?");
        $stmt -> execute([$first_name, $last_name, $email, $phone, $_SESSION['user_id']]);

        echo json_encode(['status' => 'success', 'message' => 'Profile updated!']);
        redirect("../../pages/account_settings.php#information");
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        redirect("../../pages/account_settings.php#information");
    }
} else {
    echo "Invalid request";
    redirect("../../pages/account_settings.php#information");
}

?>