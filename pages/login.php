<?php
include_once("../includes/utils.php");

if (is_logged_in()) {
    redirect("../index.php?page=home");
}

$pdo = connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please fill in the fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT user_id, email, privileges, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['privileges'] = $user['privileges'];

                redirect("../index.php?page=home");
            } else {
                $error = "Invalid email or password";
            }

        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/login.css">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome home</h1>
            <p>Please sign into your account</p>
        </div>

        <form method="POST" action="" id="login-form">
            <div class="login-form-input">
                <label for="email">Email address</label>
                <input type="email" name="email" id="email" >
            </div>

            <div class="login-form-input">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </div>

            <button type="submit" class="login-form-button" id="login-button">Login</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error): ?>
            <div class="error-message" id="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="register-link">
            Don't have an account yet? <a href="../index.php?page=register" class="register-link">Click here</a>
        </div>
    </div>
</body>
</html>