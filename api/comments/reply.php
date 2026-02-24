<?php
include '../config/headers.php';
include '../config/connectDB.php';
include '../config/auth.php';

$user = checkLogin($conn);

if(!$user){
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Not logged in"
    ]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$response = array();

if(isset($data['parentCommentID']) && isset($data['replyDetail']) && trim($data['replyDetail']) != ""){

    $parentCommentID = $data['parentCommentID'];
    $replyDetail = $data['replyDetail'];
    $userID = $user['userID'];

    $sql = "INSERT INTO comment_reply 
            (parentCommentID, userID, replyDetail, created_at, updated_at)
            VALUES 
            ('$parentCommentID', '$userID', '$replyDetail', NOW(), NOW())";

    if($conn->query($sql) === TRUE){

        $response = array(
            "status" => "success",
            "message" => "Reply created successfully",
            "replyID" => $conn->insert_id
        );

    } else {

        $response = array(
            "status" => "error",
            "message" => "Error creating reply: " . $conn->error
        );
    }

} else {

    $response = array(
        "status" => "error",
        "message" => "parentCommentID and replyDetail are required"
    );
}

echo json_encode($response);
?>