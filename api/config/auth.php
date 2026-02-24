<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkLogin($conn){
    if(isset($_SESSION['userID']) && !empty($_SESSION['userID'])){

        $userID = $_SESSION['userID'];

        $sql = "SELECT userID, firstName, lastName, nickName, email, role, gender, bio, userImage 
                FROM users 
                WHERE userID='$userID' 
                LIMIT 1";

        $result = $conn->query($sql);

        if($result && $result->num_rows > 0){
            return $result->fetch_assoc();
        }
    }

    return false;
}
?>