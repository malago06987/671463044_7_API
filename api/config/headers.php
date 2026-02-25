<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$origin = "http://localhost:5175"; // React/Vite ของมึง (แก้ให้ตรง)

header("Access-Control-Allow-Origin: $origin");   // ❗ ต้องระบุ origin ห้าม *
header("Access-Control-Allow-Credentials: true"); // ✅ เปิดให้ส่ง cookie/session
header("Access-Control-Allow-Headers: *");        // คงสไตล์เดิม
header("Access-Control-Allow-Methods: *");        // คงสไตล์เดิม
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") exit;
?>