<?php
include '../config/headers.php';
include '../config/connectDB.php';

$response = array();

// ===== pagination =====
$page  = isset($_GET['page'])  ? intval($_GET['page'])  : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;

if($page < 1)  $page = 1;
if($limit < 1) $limit = 5;

$offset = ($page - 1) * $limit;

// ===== base query =====
$sql = "SELECT 
            p.postID,
            p.postDetail,
            p.created_at,
            u.nickName,
            u.firstName,
            u.lastName,
            u.userImage,
            t.topicName,
            c.name AS categoryName
        FROM post p
        LEFT JOIN users u ON p.postBy = u.userID
        LEFT JOIN topic t ON p.postTopic = t.topicID
        LEFT JOIN categories c ON t.categoriesID = c.categoriesID
        WHERE 1";

// ===== search =====
if(isset($_GET['search']) && $_GET['search'] != ""){
    $search = $_GET['search'];
    $sql .= " AND (p.postDetail LIKE '%$search%' 
                   OR t.topicName LIKE '%$search%' 
                   OR c.name LIKE '%$search%')";
}

$sql .= " ORDER BY p.postID DESC LIMIT $offset, $limit";

$result = $conn->query($sql);

if($result && $result->num_rows > 0){

    while($row = $result->fetch_assoc()){
        $response[] = $row;
    }

} else {

    $response = array(
        "status" => "error",
        "message" => "No posts found"
    );

}

echo json_encode($response);
?>