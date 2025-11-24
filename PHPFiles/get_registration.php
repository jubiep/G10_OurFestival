<?php
header("Content-Type: application/json");

$file = "../JSONFiles/registration_data.json";

//ถ้าไฟล์ไม่มี => ส่ง JSON เปล่า
if (!file_exists($file)) {
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

// อ่านข้อมูล JSON
$data = json_decode(file_get_contents($file), true);

// ส่งข้อมูลออกไปเป็น JSON
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>