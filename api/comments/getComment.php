<?php
include '../config/headers.php';
include '../config/connectDB.php';

$response = array();

if(isset($_GET['post_id']) && $_GET['post_id'] != ""){

    $postID = $_GET['post_id'];

    $sql = "SELECT 
                c.commentID,
                c.postID,
                c.userID,
                c.commentDetail,
                c.created_at,
                u.nickName,
                u.firstName,
                u.lastName,
                u.userImage
            FROM comment c
            LEFT JOIN users u ON c.userID = u.userID
            WHERE c.postID = '$postID'
            ORDER BY c.commentID DESC";

    $result = $conn->query($sql);

    if($result && $result->num_rows > 0){

        while($row = $result->fetch_assoc()){
            $response[] = array(
                "commentID" => $row["commentID"],
                "postID" => $row["postID"],
                "userID" => $row["userID"],
                "commentDetail" => $row["commentDetail"],
                "created_at" => $row["created_at"],
                "nickName" => $row["nickName"],
                "firstName" => $row["firstName"],
                "lastName" => $row["lastName"],
                "userImage" => $row["userImage"]
            );
        }

    } else {
        $response = array(
            "status" => "error",
            "message" => "No comments found"
        );
    }

} else {
    $response = array(
        "status" => "error",
        "message" => "post_id is required"
    );
}

echo json_encode($response);
?>