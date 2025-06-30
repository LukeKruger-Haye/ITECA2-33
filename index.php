<?php

ini_set('session.cookie_lifetime',  0);
session_set_cookie_params(0);

include("includes/utils.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pages = [
    'account_settings',
    'admin',
    'cart',
    'checkout',
    'create_listing',
    'current_listing',
    'edit_listing',
    'home',
    'listings',
    'login',
    'logout',
    'messages',
    'my_listings',
    'my_orders',
    'payment_gateway',
    'register',
];

$requested_page = $_GET['page'] ?? 'home';

if (in_array($requested_page, $pages)) {
    $page_file = ROOT . "/pages/{$requested_page}.php";

    if (file_exists($page_file)) {
        redirect("pages/{$requested_page}.php");
    } else {
        error_log("Error: File not found for page: \n\n{$page_file}\n");

        redirect("errors/404.php");
    }
} else {
    redirect("errors/404.php");
}

?>