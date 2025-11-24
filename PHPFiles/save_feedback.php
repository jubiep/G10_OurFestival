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

// Ensure file exists
if (!is_file($filepath)) {
    touch($filepath, 0755);
}

// Open file for reading and writing
$fp = fopen($filepath, 'c+');

if (flock($fp, LOCK_EX)) {
    // Read existing data
    $fileSize = filesize($filepath);
    $existingData = [];

    if ($fileSize > 0) {
        $json = fread($fp, $fileSize);
        $existingData = json_decode($json, true);

        // If corrupted or not an array, reset to empty array
        if (!is_array($existingData)) {
            $existingData = [];
        }
    }

    // Append new feedback
    $existingData[] = $feedbackEntry;

    // Truncate file and write new data
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($existingData, JSON_PRETTY_PRINT));

    // Release lock
    flock($fp, LOCK_UN);
    fclose($fp);

    echo json_encode([
        'success' => true,
        'filename' => 'feedback.json',
        'message' => 'Feedback saved successfully'
    ]);
} else {
    fclose($fp);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to save feedback file'
    ]);
}
?>