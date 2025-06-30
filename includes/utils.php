<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

define("DB_HOST", "sql309.infinityfree.com");
define("DB_NAME", "if0_39156532_marketplace");
define("DB_USER", "if0_39156532");
define("DB_PASSWORD", "Ld4OBObkN7XWJda");

define("ROOT", dirname(__DIR__));

function get_key() {
    return "ges1bcldkfbjvdskjbsdfkjva5djhvoaic";
}

function connect_db() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    return $pdo;
}

function redirect($url) {
    header("Location: " . $url);
    die();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function encrypt_string($data, $key) {
    return openssl_encrypt($data, "AES-256-ECB", $key);
}

function decrypt_string($data, $key) {
    return openssl_decrypt($data, "AES-256-ECB", $key);
}


function debug_to_console($data) {
    $output = $data;

    if (is_array($output)) {
        $output = implode(',', $output);
    }

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

function compute_full_shift(&$shift_array, &$long_suffix_array, $pattern) {
    $n = strlen($pattern);
    $i = $n;
    $j = $n + 1;
    $long_suffix_array[$i] = $j;
    
    while ($i > 0) {
        while ($j <= $n && $pattern[$i - 1] != $pattern[$j - 1]) {
            if ($shift_array[$j] == 0) {
                $shift_array[$j] = $j - $i;
            }
            $j = $long_suffix_array[$j];
        }
        $i--;
        $j--;
        $long_suffix_array[$i] = $j;
    }
}

function compute_good_suffix(&$shift_array, &$long_suffix_array, $pattern) {
    $n = strlen($pattern);
    $j = $long_suffix_array[0];
    
    for ($i = 0; $i <= $n; $i++) {
        if ($shift_array[$i] == 0) {
            $shift_array[$i] = $j;
        }
        if ($i == $j) {
            $j = $long_suffix_array[$j];
        }
    }
}

function search_pattern($string, $pattern) {
    $pattern_length = strlen($pattern);
    $string_length = strlen($string);
    
    if ($pattern_length == 0 || $string_length == 0) {
        return [];
    }
    
    $longer_suffix_array = array_fill(0, $pattern_length + 1, 0);
    $shift_array = array_fill(0, $pattern_length + 1, 0);
    
    compute_full_shift($shift_array, $longer_suffix_array, $pattern);
    compute_good_suffix($shift_array, $longer_suffix_array, $pattern);
    
    $matches = [];
    $shift = 0;
    
    while ($shift <= ($string_length - $pattern_length)) {
        $j = $pattern_length - 1;
        
        while ($j >= 0 && $pattern[$j] == $string[$shift + $j]) {
            $j--;
        }
        
        if ($j < 0) {
            $matches[] = $shift;
            $shift += $shift_array[0];
        } else {
            $shift += $shift_array[$j + 1];
        }
    }
    
    return $matches;
}

function filter_listings($listings, $search_pattern) {
    if (empty($search_pattern)) {
        return $listings;
    }
    
    $search_pattern = strtolower(trim($search_pattern));
    $filtered_listings = [];
    
    foreach ($listings as $listing) {
        $name = strtolower($listing['name']);
        $description = isset($listing['description']) ? strtolower($listing['description']) : '';
        
        $name_matches = search_pattern($name, $search_pattern);
        $desc_matches = search_pattern($description, $search_pattern);
        
        if (!empty($name_matches) || !empty($desc_matches)) {
            $filtered_listings[] = $listing;
        }
    }
    
    return $filtered_listings;
}

?>