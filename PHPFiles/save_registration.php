<?php
header("Content-Type: application/json");

// รับ JSON จาก JS
$data = json_decode(file_get_contents("php://input"), true);

//ไฟล์​  JSON ที่เก็บข้อมูล
$file = "../JSONFiles/registration_data.json";

