<?php
include '../config/headers.php';
include '../config/connectDB.php';
include '../config/auth.php';

$user = checkLogin($conn);

if (!$user) {
    echo json_encode([
        "status" => "error",
        "message" => "Not logged in"
    ]);
    exit;
}

$userID = intval($user["userID"]);

// ====== Profile data ======
$sqlUser = "SELECT 
                userID,
                firstName,
                lastName,
                userName,
                email,
                bio,
                userImage,
                created_at
            FROM users
            WHERE userID = $userID
            LIMIT 1";
$resUser = $conn->query($sqlUser);

if (!$resUser || $resUser->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
    exit;
}

$profile = $resUser->fetch_assoc();

// ====== Stats ======
// posts count
$sqlPosts = "SELECT COUNT(*) AS posts_count FROM post WHERE userID = $userID";
$posts_count = 0;
if ($r = $conn->query($sqlPosts)) {
    $posts_count = intval($r->fetch_assoc()["posts_count"] ?? 0);
}

// likes received (รวมไลค์ของโพสต์ที่ user เป็นเจ้าของ)
$sqlLikes = "SELECT IFNULL(SUM(x.likes),0) AS likes_count
             FROM (
                SELECT p.postID,
                       SUM(CASE WHEN lp.value = 1 THEN 1 ELSE 0 END) AS likes
                FROM post p
                LEFT JOIN like_post lp ON lp.postID = p.postID
                WHERE p.userID = $userID
                GROUP BY p.postID
             ) x";
$likes_count = 0;
if ($r = $conn->query($sqlLikes)) {
    $likes_count = intval($r->fetch_assoc()["likes_count"] ?? 0);
}

// comments count (จำนวนคอมเมนต์ที่ user เคยคอมเมนต์)
$comments_count = 0;
// ถ้าตารางคอมเมนต์มึงชื่อ comments และมี userID จริง ใช้อันนี้ได้เลย
$sqlComments = "SELECT COUNT(*) AS comments_count FROM comments WHERE userID = $userID";
$r = $conn->query($sqlComments);
if ($r) {
    $comments_count = intval($r->fetch_assoc()["comments_count"] ?? 0);
} else {
    // ถ้าตาราง/คอลัมน์ไม่ตรง จะไม่ให้พัง ปล่อยเป็น 0
    $comments_count = 0;
}

echo json_encode([
    "status" => "success",
    "user" => $profile,
    "stats" => [
        "posts_count" => $posts_count,
        "likes_count" => $likes_count,
        "comments_count" => $comments_count
    ]
]);