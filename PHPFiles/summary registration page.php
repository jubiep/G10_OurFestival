<?php
header('Content-Type: text/html; charset=utf-8');

$regFile = __DIR__ . '/JSONFiles/registration.json';

if (!file_exists($regFile)) {
    die("<h2>No registration data found.</h2>");
}

$json = file_get_contents($regFile);
$data = json_decode($json, true);

$total = count($data);

$userTypes = [];
$interestsCount = [
    "news" => 0,
    "event" => 0
];

foreach ($data as $entry) {

    $type = $entry['usertype'] ?? 'ไม่ระบุ';
    if (!isset($userTypes[$type])) {
        $userTypes[$type] = 0;
    }
    $userTypes[$type]++;

    if (isset($entry['interests']) && is_array($entry['interests'])) {
        foreach ($entry['interests'] as $i) {
            if (isset($interestsCount[$i])) {
                $interestsCount[$i]++;
            }
        }
    }
}
?>