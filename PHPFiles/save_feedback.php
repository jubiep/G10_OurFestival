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

// Prepare feedback data
$gender = $data['gender'] ?? 'Not Prefer to Say';
$age = $data['age'] ?? 'N/A';
$booth1Rating = $data['booth1Rating'] ?? 'N/A';
$booth2Rating = $data['booth2Rating'] ?? 'N/A';
$booth3Rating = $data['booth3Rating'] ?? 'N/A';
$booth4Rating = $data['booth4Rating'] ?? 'N/A';
$favoriteBooth = $data['favoriteBooth'] ?? '0';
$comment = $data['comment'] ?? 'No comment';
$timestamp = $data['timestamp'] ?? date('Y-m-d H:i:s');

// Create filename with timestamp
$filename = 'feedback_' . date('Ymd_His') . '_' . uniqid() . '.txt';

// Path to FeedbackData directory (relative to this PHP file)
$feedbackDir = dirname(__DIR__) . '/FeedbackData/';

// Ensure directory exists
if (!is_dir($feedbackDir)) {
    mkdir($feedbackDir, 0755, true);
}

$filepath = $feedbackDir . $filename;

// Format the feedback content
$content = "Feedback Submission\n";
$content .= "==================\n\n";
$content .= "Timestamp: " . $timestamp . "\n";
$content .= "Gender: " . $gender . "\n";
$content .= "Age: " . $age . "\n\n";
$content .= "Booth Ratings:\n";
$content .= "  Booth 1: " . $booth1Rating . "/5\n";
$content .= "  Booth 2: " . $booth2Rating . "/5\n";
$content .= "  Booth 3: " . $booth3Rating . "/5\n";
$content .= "  Booth 4: " . $booth4Rating . "/5\n\n";
$content .= "Favorite Booth: " . ($favoriteBooth === '0' ? 'None' : 'Booth ' . $favoriteBooth) . "\n\n";
$content .= "Additional Comment:\n";
$content .= $comment . "\n";

// Save to file
if (file_put_contents($filepath, $content)) {
    echo json_encode([
        'success' => true,
        'filename' => $filename,
        'message' => 'Feedback saved successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to save feedback file'
    ]);
}
?>
