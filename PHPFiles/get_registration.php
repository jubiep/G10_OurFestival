<?php
header("Content-Type: application/json");

$file = "../JSONFiles/registration_data.json";

if (!file_exists($file)) {
    echo json_decode([]);
    exit;
}