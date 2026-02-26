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
$postID = $data['postID'] ?? $data['post_id'] ?? null;
$value  = $data['value'] ?? null; // 1 หรือ -1

if(!$postID || ($value != 1 && $value != -1)){
  echo json_encode(["status"=>"error","message"=>"postID and value(1|-1) required"]);
  exit();
}

$userID = $user['userID'];

$conn->begin_transaction();

try {
  // เช็คว่ามีโหวตเดิมไหม (ตาราง like_post)
  $stmt = $conn->prepare("SELECT value FROM like_post WHERE postID=? AND userID=? LIMIT 1");
  $stmt->bind_param("ii", $postID, $userID);
  $stmt->execute();
  $res = $stmt->get_result();

  $action = "created";

  if($res && $res->num_rows > 0){
    $old = (int)$res->fetch_assoc()['value'];

    if($old === (int)$value){
      // กดซ้ำค่าเดิม = ยกเลิก (ลบแถว)
      $stmt = $conn->prepare("DELETE FROM like_post WHERE postID=? AND userID=?");
      $stmt->bind_param("ii", $postID, $userID);
      $stmt->execute();
      $action = "removed";
    } else {
      // เปลี่ยน like <-> dislike
      $stmt = $conn->prepare("UPDATE like_post SET value=? WHERE postID=? AND userID=?");
      $stmt->bind_param("iii", $value, $postID, $userID);
      $stmt->execute();
      $action = "changed";
    }
  } else {
    // ยังไม่เคยโหวต = insert
    $stmt = $conn->prepare("INSERT INTO like_post(postID,userID,value) VALUES(?,?,?)");
    $stmt->bind_param("iii", $postID, $userID, $value);
    $stmt->execute();
    $action = "created";
  }

  // นับยอด likes/dislikes จาก like_post
  $stmt = $conn->prepare("
    SELECT
      SUM(CASE WHEN value=1 THEN 1 ELSE 0 END) AS likes,
      SUM(CASE WHEN value=-1 THEN 1 ELSE 0 END) AS dislikes
    FROM like_post
    WHERE postID=?
  ");
  $stmt->bind_param("i", $postID);
  $stmt->execute();
  $counts = $stmt->get_result()->fetch_assoc();

  $conn->commit();

  echo json_encode([
    "status"=>"success",
    "action"=>$action,
    "likes"=>(int)($counts['likes'] ?? 0),
    "dislikes"=>(int)($counts['dislikes'] ?? 0),
  ]);

} catch(Exception $e){
  $conn->rollback();
  echo json_encode(["status"=>"error","message"=>"Server error"]);
}
?>  