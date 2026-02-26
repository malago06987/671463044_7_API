<?php
include '../config/headers.php';
include '../config/connectDB.php';
include '../config/auth.php';

$user = checkLogin($conn);

if(!$user){
    http_response_code(401);
    echo json_encode(["status"=>"error","message"=>"Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$postID = isset($data['postID']) ? (int)$data['postID'] : 0;
$text   = trim($data['commentText'] ?? "");

if($postID <= 0 || $text === ""){
    http_response_code(400);
    echo json_encode(["status"=>"error","message"=>"Invalid data"]);
    exit;
}

$sql = "INSERT INTO comment (postID, userID, commentDetail, created_at)
        VALUES (?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $postID, $user['userID'], $text);

if($stmt->execute()){
    $newID = $conn->insert_id;

    $sql2 = "SELECT 
                c.commentID,
                c.postID,
                c.userID,
                c.commentDetail AS commentText,
                c.created_at AS createdAt,
                u.userName
             FROM comment c
             LEFT JOIN users u ON c.userID = u.userID
             WHERE c.commentID = ?
             LIMIT 1";

    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $newID);
    $stmt2->execute();
    $row = $stmt2->get_result()->fetch_assoc();

    echo json_encode(["status"=>"success","message"=>"Comment created","data"=>$row]);
} else {
    http_response_code(500);
    echo json_encode(["status"=>"error","message"=>"Error creating comment"]);
}
?>