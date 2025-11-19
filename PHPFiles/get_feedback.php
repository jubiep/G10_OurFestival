<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$feedbackDir = dirname(__DIR__) . '/JSONFiles/';
$file = $feedbackDir . 'feedback.json';

if (!file_exists($file)) {
    echo json_encode([
        'success' => true,
        'feedback' => []
    ]);
    exit;
}

$json = file_get_contents($file);
$dataArray = json_decode($json, true);

$feedbackList = [];

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

echo json_encode([
    'success' => true,
    'feedback' => $feedbackList
]);
?>