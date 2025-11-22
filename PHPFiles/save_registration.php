<?php
header("Content-Type: application/json");

// รับ JSON จาก JS
$data = json_decode(file_get_contents("php://input"), true);

// ไฟล์​  JSON ที่เก็บข้อมูล
$file = "../JSONFiles/registration_data.json";

// ถ้าไฟล์ยังไม่มีให้สร้าง
if (!file_exists($file)) {
    file_put_contents($file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

