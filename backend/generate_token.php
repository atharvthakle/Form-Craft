<?php
// generate_token.php - Generate CSRF token

// Start session FIRST before any output
session_start();

// Then set all headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:8000');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');
header('Access-Control-Allow-Credentials: true');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Generate a random token
$token = bin2hex(random_bytes(32));

// Store it in session
$_SESSION['csrf_token'] = $token;
$_SESSION['token_time'] = time();

echo json_encode([
    'success' => true,
    'token' => $token
]);
?>