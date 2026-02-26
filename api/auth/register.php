<?php
include '../config/headers.php';
include '../config/connectDB.php';
session_start();

$response = array();

/*
  ✅ สำคัญ: อัปโหลดรูปต้องใช้ multipart/form-data
  ดังนั้นข้อมูลจะมาใน $_POST และไฟล์มาใน $_FILES
*/

$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if($email !== '' && $password !== ''){

    // optional fields
    $firstName = $_POST['firstName'] ?? NULL;
    $lastName  = $_POST['lastName']  ?? NULL;
    $userName  = $_POST['userName']  ?? NULL;
    $gender    = $_POST['gender']    ?? 'unknown';
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
        echo json_encode(["status"=>"error","message"=>"Email already exists"]);
        exit();
    }

    // hash password
    $passwordToSave = password_hash($password, PASSWORD_DEFAULT);

    // ✅ อัปโหลดรูป (optional)
    $userImagePath = NULL;

    if(isset($_FILES['userImage']) && $_FILES['userImage']['error'] === UPLOAD_ERR_OK){

        $tmp  = $_FILES['userImage']['tmp_name'];
        $size = (int)$_FILES['userImage']['size'];

        // จำกัดขนาด 2MB
        if($size > 2 * 1024 * 1024){
            echo json_encode(["status"=>"error","message"=>"Image too large (max 2MB)"]);
            exit();
        }

        // เช็คชนิดไฟล์
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);

        $allow = [
            "image/jpeg" => "jpg",
            "image/png"  => "png",
            "image/webp" => "webp",
        ];

        if(!isset($allow[$mime])){
            echo json_encode(["status"=>"error","message"=>"Invalid image type"]);
            exit();
        }

        $ext = $allow[$mime];

        $dir = __DIR__ . '/../img/profile';
        if(!is_dir($dir)) mkdir($dir, 0777, true);

        $filename = 'u_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = $dir . '/' . $filename;

        if(!move_uploaded_file($tmp, $dest)){
            echo json_encode(["status"=>"error","message"=>"Upload failed"]);
            exit();
        }

        // เก็บเป็น path ไว้ใน DB
        $userImagePath = "img/profile/" . $filename;
    }

    // INSERT (เพิ่ม userImage เข้าไป)
    $sql = "INSERT INTO users (firstName, lastName, userName, email, password, role, gender, userImage, created_at, updated_at)
            VALUES (
              " . ($firstName ? "'$firstName'" : "NULL") . ",
              " . ($lastName  ? "'$lastName'"  : "NULL") . ",
              " . ($userName  ? "'$userName'"  : "NULL") . ",
              '$email',
              '$passwordToSave',
              '$role',
              '$gender',
              " . ($userImagePath ? "'$userImagePath'" : "NULL") . ",
              NOW(),
              NOW()
            )";

    if($conn->query($sql) === TRUE){
        $newID = $conn->insert_id;
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