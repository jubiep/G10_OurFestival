<?php
header("Content-Type: application/json");

// รับ JSON จาก JS
$data = json_decode(file_get_contents("php://input"), true);

// ไฟล์​  JSON ที่เก็บข้อมูล
$dir = __DIR__ . "/../JSONFiles/";
$file = $dir . "registration_data.json";

if(!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

if(!is_file($file)) {
    touch($file, 0755);
}

// ถ้าไฟล์ยังไม่มีให้สร้าง
if (!file_exists($file)) {
    file_put_contents($file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


$existing = json_decode(file_get_contents($file), true);

//ตรวจเบอร์ซ้ำ
foreach ($existing as $user) {
    if ($user["phone"] == $data["phone"]) {
        echo json_encode([
            "message" => "This phone number is used!!",
            "status" => false
        ]);
        exit;
    }
}

// ถ้าเบอร์ไม่ซ้ำ -> เพิ่มข้อมูลใหม่
$existing[] = $data;

// เขียนกลับลงไฟล์ JSON
file_put_contents($file, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode([
    "message" => "Successful!",
    "status" => true
]);
?>