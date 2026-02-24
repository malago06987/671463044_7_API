<?php
include '../config/headers.php';

include '../config/connectDB.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents("php://input"), true);

$response = array();

if(isset($data['email']) && isset($data['password'])){

    $email = $data['email'];
    $password = $data['password'];

    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = $conn->query($sql);

    if($result && $result->num_rows > 0){

        $row = $result->fetch_assoc();

        // ถ้ายังไม่ hash password ใช้แบบนี้ก่อน
        if($password == $row['password']){

            $_SESSION['userID'] = $row['userID'];

            $response = array(
                "status" => "success",
                "message" => "Login successful"
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
            "message" => "Email not found"
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