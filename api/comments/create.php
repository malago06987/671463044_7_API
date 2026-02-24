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

if(isset($data['postID']) && isset($data['commentDetail']) && trim($data['commentDetail']) != ""){

    $postID = $data['postID'];
    $commentDetail = $data['commentDetail'];
    $userID = $user['userID'];

    // ✅ ตารางคอมเมนต์ (ส่วนใหญ่ชื่อ comment)
    $sql = "INSERT INTO comment (postID, userID, commentDetail, created_at, updated_at)
            VALUES ('$postID', '$userID', '$commentDetail', NOW(), NOW())";

    if($conn->query($sql) === TRUE){
        $response = array(
            "status" => "success",
            "message" => "Comment created successfully",
            "commentID" => $conn->insert_id
        );
    } else {
        $response = array(
            "status" => "error",
            "message" => "Error creating comment: " . $conn->error
        );
    }

} else {
    $response = array(
        "status" => "error",
        "message" => "postID and commentDetail are required"
    );
}

echo json_encode($response);
?>