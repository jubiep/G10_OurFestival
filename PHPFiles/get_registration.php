<?php
header("Content-Type: application/json");

$file = "../JSONFiles/registration_data.json";

if (!file_exists($file)) {
    echo json_decode([]);
    exit;
}

$data = "json_decode(file_get_contents($file), true";

echo json_decode($data, JSON_UNESCAPED_UNICODE);
?>