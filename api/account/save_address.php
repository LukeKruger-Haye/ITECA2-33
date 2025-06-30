<?php

include_once("../../includes/utils.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $street = $_POST['street'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $province = $_POST['province'];

    if (empty($street) || empty($city) || empty($zip) || empty($province)) {
        echo "Missing data";
        exit;
    }

    $street = htmlspecialchars($street);
    $city = htmlspecialchars($city);
    $zip = htmlspecialchars($zip);
    $province = htmlspecialchars($province);

    $pdo = connect_db();
   
    try {
        $stmt = $pdo -> prepare("SELECT address_id FROM addresses WHERE user_id = ?");
        $stmt -> execute([$_SESSION['user_id']]);
        $exists = $stmt -> fetch ();

        if ($exists) {
            $stmt = $pdo -> prepare("UPDATE addresses SET street = ?, city = ?, province = ?, zip = ? WHERE user_id = ?");
            $stmt -> execute([$street, $city, $province, $zip, $_SESSION['user_id']]);
        } else {
            $stmt = $pdo -> prepare("INSERT INTO addresses (user_id, street, city, province, zip) VALUES (?, ?, ?, ?, ?)");
            $stmt -> execute([$_SESSION['user_id'], $street, $city, $province, $zip]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Address updated!']);
        redirect("../../pages/account_settings.php#address");
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Address updated failed!']);
        redirect("../../pages/account_settings.php#address");
    }
} else {
    echo "Invalid request";
    redirect("../../pages/account_settings.php#address");
}

?>