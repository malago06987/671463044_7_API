<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkLogin(mysqli $conn) {
    if (empty($_SESSION['userID'])) return false;

    $userID = (int)$_SESSION['userID'];

    $stmt = $conn->prepare("
        SELECT userID, firstName, lastName, nickName, email, role, gender, bio, userImage
        FROM users
        WHERE userID = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $userID);
    $stmt->execute();

    $res = $stmt->get_result();
    return ($res && $res->num_rows > 0) ? $res->fetch_assoc() : false;
}
?>