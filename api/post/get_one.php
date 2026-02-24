<?php
include '../config/headers.php';
include '../config/connectDB.php';

$response = array();

if(isset($_GET['id']) && $_GET['id'] != ""){

    $postID = $_GET['id'];

    // ปรับชื่อ field/table ตาม DB มึงได้
    $sql = "SELECT 
                p.postID,
                p.postDetail,
                p.postTopic,
                p.postBy,
                p.created_at,
                p.updated_at,
                u.nickName,
                u.firstName,
                u.lastName,
                u.userImage,
                t.topicName,
                t.categoriesID,
                c.name AS categoryName
            FROM post p
            LEFT JOIN users u ON p.postBy = u.userID
            LEFT JOIN topic t ON p.postTopic = t.topicID
            LEFT JOIN categories c ON t.categoriesID = c.categoriesID
            WHERE p.postID = '$postID'
            LIMIT 1";

    $result = $conn->query($sql);

    if($result && $result->num_rows > 0){

        $row = $result->fetch_assoc();

        $response = array(
            "status" => "success",
            "post" => $row
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
        "message" => "id is required"
    );

}

echo json_encode($response);
?>