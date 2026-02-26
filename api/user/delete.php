<?php
include '../config/headers.php';
include '../config/connectDB.php';
include '../config/admin.php';

$admin = checkAdmin($conn); // ต้องเป็น admin เท่านั้น

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['userID']) || $data['userID'] === '') {
    http_response_code(400);
    echo json_encode(["status"=>"error","message"=>"userID is required"]);
    exit();
}

$targetID = (int)$data['userID'];
$adminID  = (int)($admin['userID'] ?? 0);

// กันแอดมินลบตัวเอง (กันพังแบบโง่ ๆ)
if ($targetID === $adminID) {
    http_response_code(400);
    echo json_encode(["status"=>"error","message"=>"Cannot delete your own account"]);
    exit();
}

// (optional) กันลบ admin คนอื่นด้วย ถ้าอยาก
// ถ้าไม่อยากกัน ก็ตัดส่วนนี้ออก
$chk = $conn->prepare("SELECT role FROM users WHERE userID = ? LIMIT 1");
$chk->bind_param("i", $targetID);
$chk->execute();
$r = $chk->get_result();
if(!$r || $r->num_rows === 0){
    http_response_code(404);
    echo json_encode(["status"=>"error","message"=>"User not found"]);
    exit();
}
$roleRow = $r->fetch_assoc();
$chk->close();

if (($roleRow['role'] ?? '') === 'admin') {
    http_response_code(403);
    echo json_encode(["status"=>"error","message"=>"Cannot delete admin account"]);
    exit();
}

$del = $conn->prepare("DELETE FROM users WHERE userID = ?");
$del->bind_param("i", $targetID);

if (!$del->execute()) {
    http_response_code(500);
    echo json_encode([
        "status"=>"error",
        "message"=>"Error deleting user",
        "db_error"=>$del->error
    ]);
    exit();
}

if ($del->affected_rows > 0) {
    echo json_encode(["status"=>"success","message"=>"User deleted successfully"]);
} else {
    http_response_code(404);
    echo json_encode(["status"=>"error","message"=>"User not found"]);
}

$del->close();
?>