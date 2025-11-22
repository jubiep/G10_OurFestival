<?php
header("Content-Type: application/json");

// รับ JSON จาก JS
$data = json_decode(file_get_contents("php://input"), true);
