<?php
// delete_all_submissions.php - Delete all submissions

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Only POST requests are allowed'
    ]);
    exit;
}

// Path to JSON file
$json_file = '../data/submissions.json';

// Clear the file by writing empty array
if (file_put_contents($json_file, json_encode([], JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true,
        'message' => 'All submissions deleted successfully'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete submissions'
    ]);
}
?>