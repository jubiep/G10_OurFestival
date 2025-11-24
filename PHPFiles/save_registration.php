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

$existing = json_decode(file_get_contents($file), true);

//ตรวจเบอร์ซ้ำ
foreach ($existing as $user) {
    if ($user["phone"] == $data["phone"]) {
        echo json_encode([
            "message" => "เบอร์นี้ถูกใช้ลงทะเบียนแล้ว!!",
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
    "message" => "ลงทะเบียนสำเร็จ!",
    "status" => true
]);
?>