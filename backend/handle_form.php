<?php
// handle_form.php - Backend form handler

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

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Only POST requests are allowed'
    ]);
    exit;
}

// Validate CSRF token
if (!isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'CSRF token missing'
    ]);
    exit;
}

$received_token = $_SERVER['HTTP_X_CSRF_TOKEN'];

if (!isset($_SESSION['csrf_token']) || $received_token !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid CSRF token'
    ]);
    exit;
}

// Check if token is expired (valid for 1 hour)
if (!isset($_SESSION['token_time']) || (time() - $_SESSION['token_time']) > 3600) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'CSRF token expired'
    ]);
    exit;
}

// Get JSON data from request body
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

// Check if data was received
if (!$data) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON data'
    ]);
    exit;
}

// Initialize errors array
$errors = [];

// Validate Name
if (empty($data['name'])) {
    $errors[] = 'Name is required';
} elseif (!preg_match("/^[a-zA-Z\s]+$/", $data['name'])) {
    $errors[] = 'Name can only contain letters and spaces';
}

// Validate Email
if (empty($data['email'])) {
    $errors[] = 'Email is required';
} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
} else {
    // DNS check for email domain
    $email_parts = explode('@', $data['email']);
    if (count($email_parts) === 2) {
        $domain = $email_parts[1];
        if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
            $errors[] = 'Email domain does not exist';
        }
    }
}

// Validate Phone
if (empty($data['phone'])) {
    $errors[] = 'Phone is required';
} elseif (!preg_match("/^[0-9]{10}$/", $data['phone'])) {
    $errors[] = 'Phone must be exactly 10 digits';
}

// Validate Message
if (empty($data['message'])) {
    $errors[] = 'Message is required';
} elseif (strlen($data['message']) < 10) {
    $errors[] = 'Message must be at least 10 characters long';
}

// If there are validation errors, return them
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $errors
    ]);
    exit;
}

// Sanitize inputs to prevent XSS
$clean_data = [
    'name' => htmlspecialchars(strip_tags($data['name']), ENT_QUOTES, 'UTF-8'),
    'email' => htmlspecialchars(strip_tags($data['email']), ENT_QUOTES, 'UTF-8'),
    'phone' => htmlspecialchars(strip_tags($data['phone']), ENT_QUOTES, 'UTF-8'),
    'message' => htmlspecialchars(strip_tags($data['message']), ENT_QUOTES, 'UTF-8'),
    'timestamp' => date('Y-m-d H:i:s'),
    'id' => uniqid('entry_', true)
];

// Path to JSON file
$json_file = '../data/submissions.json';

// Read existing data or create empty array
if (file_exists($json_file)) {
    $existing_data = json_decode(file_get_contents($json_file), true);
    if (!is_array($existing_data)) {
        $existing_data = [];
    }
} else {
    $existing_data = [];
}

// Add new entry
$existing_data[] = $clean_data;

// Save to JSON file
if (file_put_contents($json_file, json_encode($existing_data, JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true,
        'message' => 'Form submitted successfully!',
        'data' => $clean_data
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save data'
    ]);
}
?>