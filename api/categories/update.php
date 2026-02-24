<?php
include '../config/headers.php';
include '../config/connectDB.php';
include '../config/admin.php';

$admin = checkAdmin($conn);   // ต้องเป็น admin

$data = json_decode(file_get_contents("php://input"), true);

$response = array();

if(isset($data['categoriesID']) && isset($data['name']) && trim($data['name']) != ""){

    $categoriesID = $data['categoriesID'];
    $name = $data['name'];

    // เช็คชื่อซ้ำ (ยกเว้นตัวเอง)
    $checkSql = "SELECT categoriesID FROM categories 
                 WHERE name='$name' AND categoriesID != '$categoriesID' 
                 LIMIT 1";

    $checkRes = $conn->query($checkSql);

    if($checkRes && $checkRes->num_rows > 0){

        $response = array(
            "status" => "error",
            "message" => "Category name already exists"
        );

    } else {

        $sql = "UPDATE categories 
                SET name='$name' 
                WHERE categoriesID='$categoriesID'";

        if($conn->query($sql) === TRUE){

            if($conn->affected_rows > 0){
                $response = array(
                    "status" => "success",
                    "message" => "Category updated successfully"
                );
            } else {
                $response = array(
                    "status" => "error",
                    "message" => "Category not found or no changes made"
                );
            }

        } else {

            $response = array(
                "status" => "error",
                "message" => "Error updating category"
            );

        }

    }

} else {

    $response = array(
        "status" => "error",
        "message" => "categoriesID and name are required"
    );

}

echo json_encode($response);
?>