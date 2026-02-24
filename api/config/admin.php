<?php
include 'auth.php';

function checkAdmin($conn){

    $user = checkLogin($conn);  // มาจาก auth.php

    if(!$user){
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Not logged in"
        ]);
        exit();
    }

    if(!isset($user['role']) || $user['role'] != 'admin'){
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "message" => "Forbidden (admin only)"
        ]);
        exit();
    }

    return $user;
}
?>