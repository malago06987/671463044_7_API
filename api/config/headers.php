<?php
// ห้ามมีช่องว่างก่อน <?php เด็ดขาด

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

$allow = [
  'http://localhost:5173',
  'http://localhost:5175',
];

if (in_array($origin, $allow, true)) {
  header("Access-Control-Allow-Origin: $origin");
} else {
  // ถ้ายังอยากให้ผ่านทุก origin (dev เท่านั้น)
  // header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}