<?php

include_once("../includes/utils.php");
include_once("../includes/header/header.php");

$pdo = connect_db();

try {
    $stmt = $pdo -> prepare("SELECT first_name, last_name, email, phone FROM users WHERE user_id = ?");
    $stmt -> execute([$_SESSION['user_id']]);
    $user = $stmt -> fetch();

    $stmt = $pdo -> prepare("SELECT street, city, zip, province FROM addresses WHERE user_id = ?");
    $stmt -> execute([$_SESSION['user_id']]);
    $address = $stmt -> fetch();

    $stmt = $pdo -> prepare("SELECT card_number, expiry, cvv, cardholder_name FROM cards  
    JOIN users  ON users.user_id = cards.user_id WHERE users.user_id = ?");
    $stmt -> execute([$_SESSION['user_id']]);
    $card = $stmt -> fetch();

    $key = get_key();

    if ($card) {
        $card['card_number'] = decrypt_string($card['card_number'], $key);
        $card['expiry'] = decrypt_string($card['expiry'], $key);
        $card['cvv'] = decrypt_string($card['cvv'], $key);
        $card['cardholder_name'] = decrypt_string($card['cardholder_name'], $key);
    }
} catch (PDOException $e) {
    echo "Error: " . $e -> getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/account_settings.css">
    <title>Account Settings</title>
</head>
<body>
    <br><br><br><br>    

    <div class="settings-container">
        <div class="menu">
            <h2 class="menu-title">Account Settings</h2>

            <a href="#information" class="menu-item">Personal Information</a>
            <a href="#address" class="menu-item">Address</a>
            <a href="#billing" class="menu-item">Billing Details</a>
        </div>

        <div class="form-container" id="information">
            <h2 class="form-title">Information</h2>
            <form action="../api/account/save_information.php" method="POST" id="information-form">
                <div class="form-row">
                    <div class="form-input">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo $user['first_name']; ?>">
                    </div>

                    <div class="form-input">
                        <label for="surname">Surname</label>
                        <input type="text" name="surname" id="surname" value="<?php echo $user['last_name']; ?>">
                    </div>

                    <div class="form-input">
                        <label for="email">Email Addresss</label>
                        <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>">
                    </div>

                    <div class="form-input">
                        <label for="phone">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="<?php echo $user['phone']; ?>">
                    </div>
                </div>

                <button type="submit" class="form-button" id="information-button">Save Changes</button>
            </form>
        </div>

        <div class="form-container" id="address">
            <h2 class="form-title">Address</h2>
            <form action="../api/account/save_address.php" method="POST" id="form-address">
                <div class="form-row">
                    <div class="form-input">
                        <label for="street">Street</label>
                        <input type="text" name="street" id="street" value="<?php echo $address ? htmlspecialchars($address['street']) : ''; ?>">
                    </div>

                    <div class="form-input">
                        <label for="city">City</label>
                        <input type="text" name="city" id="city" value="<?php echo $address ? htmlspecialchars($address['city']) : ''; ?>">
                    </div>

                    <div class="form-input">
                        <label for="province">Province</label>
                        <select name="province" id="province">
                            <option value="Western Cape" <?php echo ($address && $address['province'] == 'Western Cape') ? 'selected' : ''; ?>>Western Cape</option>
                            <option value="Eastern Cape" <?php echo ($address && $address['province'] == 'Eastern Cape') ? 'selected' : ''; ?>>Eastern Cape</option>
                            <option value="Northern Cape" <?php echo ($address && $address['province'] == 'Northern Cape') ? 'selected' : ''; ?>>Northern Cape</option>
                            <option value="Free State" <?php echo ($address && $address['province'] == 'Free State') ? 'selected' : ''; ?>>Free State</option>
                            <option value="KwaZulu-Natal" <?php echo ($address && $address['province'] == 'KwaZulu-Natal') ? 'selected' : ''; ?>>KwaZulu-Natal</option>
                            <option value="North West" <?php echo ($address && $address['province'] == 'North West') ? 'selected' : ''; ?>>North West</option>
                            <option value="Gauteng" <?php echo ($address && $address['province'] == 'Gauteng') ? 'selected' : ''; ?>>Gauteng</option>
                            <option value="Mpumalanga" <?php echo ($address && $address['province'] == 'Mpumalanga') ? 'selected' : ''; ?>>Mpumalanga</option>
                            <option value="Limpopo" <?php echo ($address && $address['province'] == 'Limpopo') ? 'selected' : ''; ?>>Limpopo</option>
                        </select>
                    </div>

                    <div class="form-input">
                        <label for="zip">Zip Code</label>
                        <input type="text" name="zip" id="zip" value="<?php echo $address ? htmlspecialchars($address['zip']) : ''; ?>">
                    </div>
                </div>

                <button type="submit" class="form-button" id="address-button">Save Address</button>
            </form>
        </div>

        <div class="form-container" id="billing">
            <h2 class="form-title">Billing Information</h2>
            <form action="../api/account/save_card.php" method="POST" id="form-billing">
                <div class="form-row">
                    <div class="form-input">
                        <label for="card_number">Card Number</label>
                        <input type="text" name="card_number" id="card_number" value="<?php echo $card ? htmlspecialchars($card['card_number']) : ''; ?>">
                    </div>

                    <div class="form-input">
                        <label for="expiry">Expiry Date</label>
                        <input type="text" name="expiry" id="expiry" value="<?php echo $card ? htmlspecialchars($card['expiry']) : ''; ?>">
                    </div>

                    <div class="form-input">
                        <label for="cvv">CVV</label>
                        <input type="text" name="cvv" id="cvv" value="<?php echo $card ? htmlspecialchars($card['cvv']) : ''; ?>">
                    </div>

                    <div class="form-input">
                        <label for="card_name">Cardholder Name</label>
                        <input type="text" name="card_name" id="card_name" value="<?php echo $card ? htmlspecialchars($card['cardholder_name']) : ''; ?>">
                    </div>
                </div>

                <button type="submit" class="form-button" id="billing-button">Save Changes</button>
            </form> 
        </div>
    </div>
</body>
</html>

<?php

include_once("../includes/footer/footer.php");

?>