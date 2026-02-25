<?php
include '../config/headers.php';
include '../config/connectDB.php';

$response = [];

// ===== search =====
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// ===== base query =====
$sql = "SELECT 
            p.postID,
            p.postDetail,
            p.postImage,
            p.created_at,
            u.userName,
            u.firstName,
            u.lastName,
            u.userImage,
            t.topicName,
            c.name AS categoryName,
            IFNULL(lc.likeCount, 0) AS likeCount
        FROM post p
        LEFT JOIN users u ON p.userID = u.userID
        LEFT JOIN topic t ON p.topicID = t.topicID
        LEFT JOIN categories c ON t.categoriesID = c.categoriesID
        LEFT JOIN (
            SELECT postID, COUNT(*) AS likeCount
            FROM like_post
            WHERE value = 1
            GROUP BY postID
        ) lc ON p.postID = lc.postID
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