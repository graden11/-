<?php
// 连接到数据库
$servername = 'localhost';
$mysqli_username = 'root';
$mysqli_password = '123456';
$dbname = 'shopping';

$conn = new mysqli($servername, $mysqli_username, $mysqli_password, $dbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>