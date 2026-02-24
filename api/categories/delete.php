<?php
include '../config/headers.php';
include '../config/connectDB.php';
include '../config/admin.php';

$admin = checkAdmin($conn);   // ต้องเป็น admin เท่านั้น

$data = json_decode(file_get_contents("php://input"), true);

$response = array();

if(isset($data['categoriesID']) && !empty($data['categoriesID'])){

    $categoriesID = $data['categoriesID'];

    $sql = "DELETE FROM categories WHERE categoriesID='$categoriesID'";

    if($conn->query($sql) === TRUE){

        if($conn->affected_rows > 0){
            $response = array(
                "status" => "success",
                "message" => "Category deleted successfully"
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => "Category not found"
            );
        }

    } else {

        $response = array(
            "status" => "error",
            "message" => "Error deleting category"
        );

    }

} else {

    $response = array(
        "status" => "error",
        "message" => "categoriesID is required"
    );

}

echo json_encode($response);
?>