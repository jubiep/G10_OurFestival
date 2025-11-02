<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Path to FeedbackData directory (relative to this PHP file)
$feedbackDir = dirname(__DIR__) . '/FeedbackData/';

// Check if directory exists
if (!is_dir($feedbackDir)) {
    echo json_encode([
        'success' => true,
        'feedback' => []
    ]);
    exit;
}

// Get all txt files from FeedbackData directory
$files = glob($feedbackDir . 'feedback_*.txt');

$feedbackList = [];

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Parse the feedback file
    $feedback = parseFeedbackFile($content, basename($file));
    
    if ($feedback) {
        $feedbackList[] = $feedback;
    }
}

echo json_encode([
    'success' => true,
    'feedback' => $feedbackList
]);

// Function to parse feedback file content
function parseFeedbackFile($content, $filename) {
    $lines = explode("\n", $content);
    $feedback = [
        'filename' => $filename,
        'timestamp' => '',
        'gender' => '',
        'age' => '',
        'booth1Rating' => '',
        'booth2Rating' => '',
        'booth3Rating' => '',
        'booth4Rating' => '',
        'favoriteBooth' => '0',
        'comment' => ''
    ];
    
    $inComment = false;
    $commentLines = [];
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        if (empty($line) || $line === 'Feedback Submission' || strpos($line, '===') === 0) {
            continue;
        }
        
        if (strpos($line, 'Timestamp:') === 0) {
            $feedback['timestamp'] = trim(str_replace('Timestamp:', '', $line));
        } elseif (strpos($line, 'Gender:') === 0) {
            $feedback['gender'] = trim(str_replace('Gender:', '', $line));
        } elseif (strpos($line, 'Age:') === 0) {
            $feedback['age'] = trim(str_replace('Age:', '', $line));
        } elseif (strpos($line, 'Booth 1:') === 0) {
            $rating = trim(str_replace('Booth 1:', '', $line));
            $feedback['booth1Rating'] = str_replace('/5', '', trim($rating));
        } elseif (strpos($line, 'Booth 2:') === 0) {
            $rating = trim(str_replace('Booth 2:', '', $line));
            $feedback['booth2Rating'] = str_replace('/5', '', trim($rating));
        } elseif (strpos($line, 'Booth 3:') === 0) {
            $rating = trim(str_replace('Booth 3:', '', $line));
            $feedback['booth3Rating'] = str_replace('/5', '', trim($rating));
        } elseif (strpos($line, 'Booth 4:') === 0) {
            $rating = trim(str_replace('Booth 4:', '', $line));
            $feedback['booth4Rating'] = str_replace('/5', '', trim($rating));
        } elseif (strpos($line, 'Favorite Booth:') === 0) {
            $fav = trim(str_replace('Favorite Booth:', '', $line));
            if (strpos($fav, 'Booth') === 0) {
                $feedback['favoriteBooth'] = trim(str_replace('Booth', '', $fav));
            } else {
                $feedback['favoriteBooth'] = '0';
            }
        } elseif (strpos($line, 'Additional Comment:') === 0) {
            $inComment = true;
            continue;
        }
        
        if ($inComment && !empty($line)) {
            $commentLines[] = $line;
        }
    }
    
    $feedback['comment'] = implode("\n", $commentLines);
    
    return $feedback;
}
?>
