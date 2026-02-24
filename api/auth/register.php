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

    // optional fields
    $firstName = isset($data['firstName']) ? $data['firstName'] : NULL;
    $lastName  = isset($data['lastName']) ? $data['lastName'] : NULL;
    $userName  = isset($data['userName']) ? $data['userName'] : NULL;
    $gender    = isset($data['gender']) ? $data['gender'] : 'unknown';
    $role      = 'user';

    // basic validation
    if(trim($email) == "" || trim($password) == ""){
        echo json_encode(["status"=>"error","message"=>"Email/Password is required"]);
        exit();
    }

    // เช็ค email ซ้ำ
    $checkSql = "SELECT userID FROM users WHERE email='$email' LIMIT 1";
    $checkRes = $conn->query($checkSql);

    if($checkRes && $checkRes->num_rows > 0){
        $response = array("status"=>"error","message"=>"Email already exists");
        echo json_encode($response);
        exit();
    }

    // ถ้าอาจารย์ยังไม่สอน hash ใช้ตรงนี้ก่อน (plain)
    $passwordToSave = $password;

    // ✅ ถ้าจะให้ดีขึ้น (แนะนำ) ให้ใช้ hash:
    // $passwordToSave = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (firstName, lastName, userName, email, password, role, gender, created_at, updated_at)
            VALUES (
              " . ($firstName ? "'$firstName'" : "NULL") . ",
              " . ($lastName  ? "'$lastName'"  : "NULL") . ",
              " . ($userName  ? "'$userName'"  : "NULL") . ",
              '$email',
              '$passwordToSave',
              '$role',
              '$gender',
              NOW(),
              NOW()
            )";

    if($conn->query($sql) === TRUE){
        $newID = $conn->insert_id;

        // จะ auto-login หลังสมัครก็ได้ (สะดวก)
        $_SESSION['userID'] = $newID;

        $response = array("status"=>"success","message"=>"Register successful");
    } else {
        $response = array("status"=>"error","message"=>"Error: ".$conn->error);
    }

} else {
    $response = array("status"=>"error","message"=>"Invalid request");
}

echo json_encode($response);
?>