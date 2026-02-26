<?php
include '../config/headers.php';
include '../config/connectDB.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents("php://input"), true);
$response = array();

if(isset($data['userName']) && isset($data['password'])){

    $userName = $data['userName'];
    $password = $data['password'];

    $sql = "SELECT * FROM users WHERE userName='$userName' LIMIT 1";
    $result = $conn->query($sql);

    if($result && $result->num_rows > 0){

        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {

          $_SESSION['userID'] = $row['userID'];

$response = array(
    "status" => "success",
    "message" => "Login successful",
    "user" => array(
        "userID"   => $row['userID'],
        "userName" => $row['userName'],
        "role"     => $row['role'] ?? "user",
        "userImage"=> $row['userImage'] ?? null
    )
);

        } else {

            $response = array(
                "status" => "error",
                "message" => "Password incorrect"
            );
        }

    } else {

        $response = array(
            "status" => "error",
            "message" => "Username not found"
        );
    }

} else {

    $response = array(
        "status" => "error",
        "message" => "Invalid request"
    );
}

echo json_encode($response);
?>