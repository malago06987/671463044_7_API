<?php
include '../config/headers.php';
include '../config/connectDB.php';

$postID = $_GET['post_id'] ?? null;

if(!$postID){
    http_response_code(400);
    echo json_encode(["status"=>"error","message"=>"post_id is required","data"=>[]]);
    exit;
}

$postID = (int)$postID;

$sql = "SELECT 
            c.commentID,
            c.postID,
            c.userID,
            c.commentDetail AS commentText,
            c.created_at AS createdAt,
            u.userName
        FROM comment c
        LEFT JOIN users u ON c.userID = u.userID
        WHERE c.postID = ?
        ORDER BY c.commentID DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postID);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode(["status"=>"success","message"=>"OK","data"=>$data]);
?>