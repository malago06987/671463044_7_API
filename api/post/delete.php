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

if(isset($data['postID']) && !empty($data['postID'])){

    $postID = $data['postID'];
    $userID = $user['userID'];
    $role = $user['role'];

    // เช็คก่อนว่าโพสต์นี้เป็นของใคร
    $checkSql = "SELECT postBy FROM post WHERE postID='$postID' LIMIT 1";
    $checkRes = $conn->query($checkSql);

    if($checkRes && $checkRes->num_rows > 0){

        $row = $checkRes->fetch_assoc();
        $ownerID = $row['postBy'];

        // ลบได้ถ้าเป็นเจ้าของ หรือ admin
        if($ownerID == $userID || $role == 'admin'){

            $sql = "DELETE FROM post WHERE postID='$postID'";

            if($conn->query($sql) === TRUE){

                if($conn->affected_rows > 0){
                    $response = array(
                        "status" => "success",
                        "message" => "Post deleted successfully"
                    );
                } else {
                    $response = array(
                        "status" => "error",
                        "message" => "Post not found"
                    );
                }

            } else {
                $response = array(
                    "status" => "error",
                    "message" => "Error deleting post"
                );
            }

        } else {
            http_response_code(403);
            $response = array(
                "status" => "error",
                "message" => "Forbidden"
            );
        }

    } else {
        $response = array(
            "status" => "error",
            "message" => "Post not found"
        );
    }

} else {
    $response = array(
        "status" => "error",
        "message" => "postID is required"
    );
}

echo json_encode($response);
?>