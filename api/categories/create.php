<?php
include '../config/headers.php';
include '../config/connectDB.php';
include '../config/admin.php';

$admin = checkAdmin($conn);   // ต้องเป็น admin ก่อน

$data = json_decode(file_get_contents("php://input"), true);

$response = array();

if(isset($data['name']) && trim($data['name']) != ""){

    $name = $data['name'];

    // เช็คชื่อซ้ำ
    $checkSql = "SELECT categoriesID FROM categories WHERE name='$name' LIMIT 1";
    $checkRes = $conn->query($checkSql);

    if($checkRes && $checkRes->num_rows > 0){

        $response = array(
            "status" => "error",
            "message" => "Category already exists"
        );

    } else {

        $sql = "INSERT INTO categories (name) VALUES ('$name')";

        if($conn->query($sql) === TRUE){
            $response = array(
                "status" => "success",
                "message" => "Category created successfully"
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => "Error inserting category"
            );
        }

    }

} else {

    $response = array(
        "status" => "error",
        "message" => "Category name is required"
    );

}

echo json_encode($response);
?>