<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Define the directory for the JSON file
$feedbackDir = dirname(__DIR__) . '/JSONFiles/';
$file = $feedbackDir . 'feedback.json';

// Check $file exists
if (!file_exists($file)) {
    echo json_encode([
        'success' => true,
        'feedback' => []
    ]);
    exit;
}

// Read $file
$json = file_get_contents($file);
$dataArray = json_decode($json, true);

//initialize $feedbackList prepare for return
$feedbackList = [];

// append value for each $data to $feedbackList
if (is_array($dataArray)) {
    foreach ($dataArray as $index => $data) {
        if (!is_array($data)) continue;

        if (isset($data['boothRatings']) && is_array($data['boothRatings'])) {
            $data['booth1Rating'] = $data['boothRatings']['booth1'] ?? null;
            $data['booth2Rating'] = $data['boothRatings']['booth2'] ?? null;
            $data['booth3Rating'] = $data['boothRatings']['booth3'] ?? null;
            $data['booth4Rating'] = $data['boothRatings']['booth4'] ?? null;
            unset($data['boothRatings']);
        }

        $data['filename'] = basename($file);
        $data['index'] = $index;

        $feedbackList[] = $data;
    }
}

// return $feedbackList
echo json_encode([
    'success' => true,
    'feedback' => $feedbackList
]);
?>