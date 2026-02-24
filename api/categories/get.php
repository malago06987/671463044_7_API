<?php
include '../config/headers.php';
include '../config/connectDB.php';

$response = array();

$sql = "SELECT categoriesID, name FROM categories WHERE 1";

// optional search
if(isset($_GET['search']) && $_GET['search'] != ""){
    $search = $_GET['search'];
    $sql .= " AND name LIKE '%$search%'";
}

$sql .= " ORDER BY categoriesID DESC";

$result = $conn->query($sql);

if($result && $result->num_rows > 0){

    while($row = $result->fetch_assoc()){
        $response[] = array(
            "categoriesID" => $row["categoriesID"],
            "name" => $row["name"]
        );
    }

} else {

    $response = array(
        "status" => "error",
        "message" => "No categories found"
    );

}

echo json_encode($response);
?>