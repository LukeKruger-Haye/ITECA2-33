<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/header/style.css">
</head>
<header>
    <nav>
    <div class="navbar-container">
        <div class="logo">
            <a href="../index.php?page=home">Marketplace</a>
        </div>

        <ul class="navbar-links">
            <li><a href="../index.php?page=home" class="navbar-link">Home</a></li>
            <li><a href="../index.php?page=listings" class="navbar-link">Listings</a></li>

            <?php 
            if (session_status() == PHP_SESSION_NONE) {
                ini_set('session.cookie_lifetime', 0);
                session_set_cookie_params(0);
                session_start();
            }
           
            if (isset($_SESSION['privileges']) && $_SESSION['privileges'] === 'admin') {
                echo '<li><a href="../index.php?page=admin" class="navbar-link">Admin</a></li>';
            }

            if (isset($_SESSION['user_id'])) {
                echo '<li><a href="../index.php?page=cart" class="navbar-link">Cart</a></li>
                <div class="dropdown-account-menu">
                    <a href="#" class="navbar-link">My Account</a>
                    
                    <div class="dropdown-account-content">
                        <a href="../index.php?page=messages" class="dropdown-account-link">Messages</a>
                        <a href="../index.php?page=my_listings" class="dropdown-account-link">My Listings</a>
                        <a href="../index.php?page=my_orders" class="dropdown-account-link">My Orders</a>
                        <a href="../index.php?page=account_settings" class="dropdown-account-link">Settings</a>
                        <a href="../index.php?page=logout" class="dropdown-account-link">Logout</a>
                    </div>
                </div>';
            } else {
                echo '<li><a href="../index.php?page=login" class="navbar-link">Login</a></li>';
            }
            ?>
        </ul>
    </div>
    </nav>

    <script src="../includes/header/index.js"></script>
</header>
</html>