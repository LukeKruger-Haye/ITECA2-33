<?php

include_once("../includes/utils.php");

$pdo = connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);

    $password = trim($_POST['password']);
    $confirm_pw = trim($_POST['confirm-password']);

    if (empty($email) || empty($password) || empty($confirm_pw) || empty($name) || empty($surname)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirm_pw) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $stmt = $pdo -> prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt -> execute([$email]);

            if ($stmt -> fetch()) {
                $error = 'An account with this email already exists.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo -> prepare("INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)");
                $stmt -> execute([$email, $hashed_password, $name, $surname]);

                redirect('../index.php?page=login');
            }

        } catch (PDOException $e) {
            $error = 'An error occurred. Please try again.';

            echo "{$error}";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/register.css">
    <title>Register</title>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Create your account</h1>
            <p>Join us today</p>
        </div>

        <form method="POST" action="" id="register-form">
            <div class="register-form-input">
                <label for="name">First name</label>
                <input type="text" name="name" id="name">
            </div>

            <div class="register-form-input">
                <label for="surname">Surname</label>
                <input type="text" name="surname" id="surname">
            </div>

            <div class="register-form-input">
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
            </div>

            <div class="register-form-input">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </div>

            <div class="register-form-input">
                <label for="confirm-password">Confirm password</label>
                <input type="password" name="confirm-password" id="confirm-password">
            </div>

            <button class="register-form-button" type="submit">Register</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="../index.php?page=login">Log in here</a>
        </div>
    </div>
</body>
</html>