<?php
include '../config/headers.php';
include '../config/connectDB.php';
include '../config/auth.php';

$user = checkLogin($conn);

if(!$user){
    http_response_code(401);
    echo json_encode(["status"=>"error","message"=>"Not logged in"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['postID']) || $data['postID'] === ''){
    http_response_code(400);
    echo json_encode(["status"=>"error","message"=>"postID is required"]);
    exit();
}

$postID = (int)$data['postID'];
$userID = (int)$user['userID'];
$role   = $user['role'] ?? '';

/* 1) เช็คเจ้าของโพสต์ (ใช้ userID ในตาราง post) */
$stmt = $conn->prepare("SELECT userID FROM post WHERE postID = ? LIMIT 1");
$stmt->bind_param("i", $postID);

if(!$stmt->execute()){
    http_response_code(500);
    echo json_encode(["status"=>"error","message"=>"DB error", "db_error"=>$stmt->error]);
    exit();
}

$res = $stmt->get_result();
if(!$res || $res->num_rows === 0){
    http_response_code(404);
    echo json_encode(["status"=>"error","message"=>"Post not found"]);
    exit();
}

$row = $res->fetch_assoc();
$ownerID = (int)$row['userID'];
$stmt->close();

/* 2) อนุญาตลบ: เจ้าของ หรือ admin */
if($ownerID !== $userID && $role !== 'admin'){
    http_response_code(403);
    echo json_encode(["status"=>"error","message"=>"Forbidden"]);
    exit();
}

/* 3) ลบ */
$del = $conn->prepare("DELETE FROM post WHERE postID = ?");
$del->bind_param("i", $postID);

if(!$del->execute()){
    http_response_code(500);
    echo json_encode([
        "status"=>"error",
        "message"=>"Error deleting post",
        "db_error"=>$del->error
    ]);
    exit();
}

if($del->affected_rows > 0){
    echo json_encode(["status"=>"success","message"=>"Post deleted successfully"]);
} else {
    http_response_code(404);
    echo json_encode(["status"=>"error","message"=>"Post not found"]);
}
$del->close();
?>