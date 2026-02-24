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

if(isset($data['postDetail']) && isset($data['postTopic']) && trim($data['postDetail']) != ""){

    $postDetail = $data['postDetail'];
    $postTopic = $data['postTopic'];
    $userID = $user['userID'];

    // แก้ postTopic เป็น topicID และแก้ postBy เป็น userID
$sql = "INSERT INTO post 
        (postDetail, topicID, userID, created_at, updated_at)
        VALUES 
        ('$postDetail', '$postTopic', '$userID', NOW(), NOW())";

    if($conn->query($sql) === TRUE){

        $response = array(
            "status" => "success",
            "message" => "Post created successfully",
            "postID" => $conn->insert_id
        );

    } else {

        $response = array(
            "status" => "error",
            "message" => "Error creating post: " . $conn->error
        );
    }

} else {

    $response = array(
        "status" => "error",
        "message" => "postDetail and postTopic are required"
    );
}

echo json_encode($response);
?>