<?php
// delete_submission.php - Delete a single submission by ID

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

// Get JSON data
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

// Validate input
if (!isset($data['id']) || empty($data['id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Submission ID is required'
    ]);
    exit;
}

$id_to_delete = $data['id'];

// Path to JSON file
$json_file = '../data/submissions.json';

// Check if file exists
if (!file_exists($json_file)) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'No submissions found'
    ]);
    exit;
}

// Read existing data
$existing_data = json_decode(file_get_contents($json_file), true);

if (!is_array($existing_data)) {
    $existing_data = [];
}

// Filter out the submission with matching ID
$filtered_data = array_filter($existing_data, function($submission) use ($id_to_delete) {
    return $submission['id'] !== $id_to_delete;
});

// Re-index array
$filtered_data = array_values($filtered_data);

// Save back to file
if (file_put_contents($json_file, json_encode($filtered_data, JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true,
        'message' => 'Submission deleted successfully',
        'remaining' => count($filtered_data)
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save changes'
    ]);
}
?>