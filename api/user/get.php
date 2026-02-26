<?php
include '../config/headers.php';
include '../config/connectDB.php';
include '../config/admin.php';

$admin = checkAdmin($conn);   // ต้องเป็น admin เท่านั้น

$sql = "SELECT userID, userName, email, role FROM users ORDER BY userID DESC";
$result = $conn->query($sql);

$data = array();

if($result){
    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }
}

echo json_encode($data);
?>