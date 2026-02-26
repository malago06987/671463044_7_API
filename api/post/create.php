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

$topicID = (int)($_POST["topicID"] ?? 0);
$postDetail = trim($_POST["postDetail"] ?? "");

if($topicID<=0 || $postDetail===""){
  http_response_code(400);
  echo json_encode(["status"=>"error","message"=>"topicID and postDetail are required"]);
  exit();
}

if(!isset($_FILES["postImage"]) || $_FILES["postImage"]["error"] !== UPLOAD_ERR_OK){
  http_response_code(400);
  echo json_encode(["status"=>"error","message"=>"postImage is required"]);
  exit();
}

// อัปโหลดรูป (ตัวอย่างแบบง่าย)
$dir = __DIR__ . "/../img/post/";
if(!is_dir($dir)) mkdir($dir, 0777, true);

$ext = pathinfo($_FILES["postImage"]["name"], PATHINFO_EXTENSION);
$fname = uniqid("post_", true) . "." . $ext;
$path = $dir . $fname;

if(!move_uploaded_file($_FILES["postImage"]["tmp_name"], $path)){
  http_response_code(500);
  echo json_encode(["status"=>"error","message"=>"Upload failed"]);
  exit();
}

// path ที่เก็บใน DB (ปรับตามโปรเจกต์มึง)
$postImage = "img/post/" . $fname;

$userID = (int)$user["userID"];
$postDetailEsc = $conn->real_escape_string($postDetail);

$sql = "INSERT INTO post (postDetail, topicID, userID, postImage, created_at, updated_at)
        VALUES ('$postDetailEsc',$topicID,$userID,'$postImage',NOW(),NOW())";

if($conn->query($sql)){
  echo json_encode(["status"=>"success","postID"=>$conn->insert_id,"postImage"=>$postImage]);
}else{
  http_response_code(500);
  echo json_encode(["status"=>"error","message"=>$conn->error]);
}