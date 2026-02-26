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

if(isset($data['commentID']) && !empty($data['commentID'])){

    $commentID = (int)$data['commentID'];
    $userID = (int)$user['userID'];
    $role = $user['role'] ?? '';

    // 1) เช็คก่อนว่า comment นี้เป็นของใคร
    $checkSql = "SELECT userID FROM comment WHERE commentID = ? LIMIT 1";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("i", $commentID);
    $stmt->execute();
    $checkRes = $stmt->get_result();

    if($checkRes && $checkRes->num_rows > 0){

        $row = $checkRes->fetch_assoc();
        $ownerID = (int)$row['userID'];

        // 2) ลบได้ถ้าเป็นเจ้าของ หรือ admin
        if($ownerID === $userID || $role === 'admin'){

            $sql = "DELETE FROM comment WHERE commentID = ?";
            $stmt2 = $conn->prepare($sql);
            $stmt2->bind_param("i", $commentID);

            if($stmt2->execute()){

                if($stmt2->affected_rows > 0){
                    $response = array(
                        "status" => "success",
                        "message" => "Comment deleted successfully"
                    );
                } else {
                    $response = array(
                        "status" => "error",
                        "message" => "Comment not found"
                    );
                }

            } else {
                $response = array(
                    "status" => "error",
                    "message" => "Error deleting comment"
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
            "message" => "Comment not found"
        );
    }

} else {
    $response = array(
        "status" => "error",
        "message" => "commentID is required"
    );
}

echo json_encode($response);
?>