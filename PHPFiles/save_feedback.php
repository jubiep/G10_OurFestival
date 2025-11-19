<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit;
}

// Prepare feedback entry
$feedbackEntry = [
    'timestamp' => $data['timestamp'] ?? date('Y-m-d H:i:s'),
    'gender' => $data['gender'] ?? 'Not Prefer to Say',
    'age' => $data['age'] ?? 'N/A',
    'boothRatings' => [
        'booth1' => $data['booth1Rating'] ?? 'N/A',
        'booth2' => $data['booth2Rating'] ?? 'N/A',
        'booth3' => $data['booth3Rating'] ?? 'N/A',
        'booth4' => $data['booth4Rating'] ?? 'N/A'
    ],
    'favoriteBooth' => $data['favoriteBooth'] ?? '0',
    'comment' => $data['comment'] ?? 'No comment'
];

// Directory + file path
$feedbackDir = dirname(__DIR__) . '/JSONFiles/';
$filepath = $feedbackDir . 'feedback.json';

// Ensure directory exists
if (!is_dir($feedbackDir)) {
    mkdir($feedbackDir, 0755, true);
}

// Read existing data (if file exists)
$existingData = [];

if (file_exists($filepath)) {
    $json = file_get_contents($filepath);
    $existingData = json_decode($json, true);

    // If corrupted or not an array, reset to empty array
    if (!is_array($existingData)) {
        $existingData = [];
    }
}

// Append new feedback
$existingData[] = $feedbackEntry;

// Save back to file
if (file_put_contents($filepath, json_encode($existingData, JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true,
        'filename' => 'feedback.json',
        'message' => 'Feedback saved successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to save feedback file'
    ]);
}
?>