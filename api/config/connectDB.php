<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "671463044_7_react";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8");
?>
