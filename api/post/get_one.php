<?php
include '../config/headers.php';
include '../config/connectDB.php';

if (!isset($_GET['id']) || trim($_GET['id']) === "") {
    echo json_encode([
        "status" => "error",
        "message" => "id is required"
    ]);
    exit;
}

$postID = $_GET['id'];

$sql = "SELECT 
            p.postID,
            p.topicID,
            p.userID,
            p.postDetail AS content,
            p.postImage AS img,
            p.created_at,
            p.updated_at,

            t.topicName AS title,
            t.categoriesID,
            c.name AS category_name,

            u.userImage,
            COALESCE(NULLIF(u.userName,''), CONCAT_WS(' ', u.firstName, u.lastName)) AS userName,

            -- ✅ นับจาก like_post (ของจริงที่มึงใช้)
            COALESCE((
              SELECT SUM(CASE WHEN lp.value=1 THEN 1 ELSE 0 END)
              FROM like_post lp
              WHERE lp.postID = p.postID
            ),0) AS likes,

            COALESCE((
              SELECT SUM(CASE WHEN lp.value=-1 THEN 1 ELSE 0 END)
              FROM like_post lp
              WHERE lp.postID = p.postID
            ),0) AS dislikes

        FROM post p
        LEFT JOIN topic t ON p.topicID = t.topicID
        LEFT JOIN categories c ON t.categoriesID = c.categoriesID
        LEFT JOIN users u ON p.userID = u.userID
        WHERE p.postID = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postID);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "status" => "success",
        "data" => $row
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Post not found"
    ]);
}
?>