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

if(isset($data['postID']) && !empty($data['postID'])){

    $postID = $data['postID'];

    $sql = "UPDATE post SET likes = likes + 1 WHERE postID='$postID'";

    if($conn->query($sql) === TRUE){
        echo json_encode(["status"=>"success","message"=>"Liked"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Error: ".$conn->error]);
    }

} else {
    echo json_encode(["status"=>"error","message"=>"postID is required"]);
}
?>