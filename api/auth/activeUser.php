<?php
include '../config/headers.php';
include '../config/connectDB.php';
include '../config/auth.php';

$user = checkLogin($conn);

if($user){
    $response = array(
        "status" => "success",
        "user" => $user
    );
} else {
    $response = array(
        "status" => "error",
        "message" => "Not logged in"
    );
}

echo json_encode($response);
?>