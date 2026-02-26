<?php
require_once __DIR__ . '/../config/headers.php';
require_once __DIR__ . '/../config/connectDB.php';
require_once __DIR__ . '/../config/auth.php';

$user = checkLogin($conn);
if(!$user){
  http_response_code(401);
  echo json_encode(["status"=>"error","message"=>"Not logged in"]);
  exit();
}

$topicName = trim($_POST["topicName"] ?? "");
$categoriesID = (int)($_POST["categoriesID"] ?? 0);

if($topicName==="" || $categoriesID<=0){
  http_response_code(400);
  echo json_encode(["status"=>"error","message"=>"topicName and categoriesID are required"]);
  exit();
}

$userID = (int)$user["userID"];
$topicNameEsc = $conn->real_escape_string($topicName);

$sql = "INSERT INTO topic (topicName,categoriesID,userID,created_at,updated_at)
        VALUES ('$topicNameEsc',$categoriesID,$userID,NOW(),NOW())";

if($conn->query($sql)){
  echo json_encode(["status"=>"success","topicID"=>$conn->insert_id]);
}else{
  http_response_code(500);
  echo json_encode(["status"=>"error","message"=>$conn->error]);
}