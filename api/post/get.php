<?php
include '../config/headers.php';
include '../config/connectDB.php';

$response = [];

// ===== search =====
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// ===== base query =====
$sql = "SELECT 
            p.postID,
            p.userID,
            p.postDetail,
            p.postImage,
            p.created_at,
            u.userName,
            u.firstName,
            u.lastName,
            u.userImage,
            t.topicName,
            c.name AS categoryName,

            -- ✅ ส่งชื่อ field มาตรฐานให้ฝั่ง React ใช้เลย
            IFNULL(vc.likes, 0) AS likes,
            IFNULL(vc.dislikes, 0) AS dislikes

        FROM post p
        LEFT JOIN users u ON p.userID = u.userID
        LEFT JOIN topic t ON p.topicID = t.topicID
        LEFT JOIN categories c ON t.categoriesID = c.categoriesID

        -- ✅ รวม count likes/dislikes จาก like_post
        LEFT JOIN (
            SELECT 
                postID,
                SUM(CASE WHEN value = 1 THEN 1 ELSE 0 END) AS likes,
                SUM(CASE WHEN value = -1 THEN 1 ELSE 0 END) AS dislikes
            FROM like_post
            GROUP BY postID
        ) vc ON p.postID = vc.postID

        WHERE 1";

// ===== search condition =====
if ($search !== "") {
    $safe = $conn->real_escape_string($search);
    $sql .= " AND (
                p.postDetail LIKE '%$safe%' 
                OR t.topicName LIKE '%$safe%' 
                OR c.name LIKE '%$safe%'
                OR u.userName LIKE '%$safe%'
             )";
}

// ===== filter by category =====
if (isset($_GET['categoriesID']) && $_GET['categoriesID'] !== "") {
    $catId = intval($_GET['categoriesID']);
    $sql .= " AND c.categoriesID = $catId";
}

$sql .= " ORDER BY p.postID DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    $response = [];
}

echo json_encode($response);
?>