<?php
// get_submissions.php - Retrieve all submissions

header('Content-Type: application/json');

// Path to JSON file
$json_file = '../data/submissions.json';

// Check if file exists
if (!file_exists($json_file)) {
    echo json_encode([
        'success' => true,
        'submissions' => []
    ]);
    exit;
}

// Read the file
$json_data = file_get_contents($json_file);
$submissions = json_decode($json_data, true);

// Check if valid array
if (!is_array($submissions)) {
    $submissions = [];
}

echo json_encode([
    'success' => true,
    'submissions' => $submissions,
    'count' => count($submissions)
]);
?>