<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// 连接数据库或者其他必要的操作
require_once("db_connection.php"); // 假设这是连接数据库的脚本

// 获取通过 AJAX 传递的邮箱地址
$email = $_POST['email'];

// 检查用户表中是否存在给定的邮箱地址
$sql = "SELECT * FROM user WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $sql1="SELECT password from user email='$email'";
    $result1=$conn->query($sql1);
    if($result->num_rows >0){
        
    }
    $response = array('success' => true, 'message' => '重置密码链接已发送到您的邮箱，请查收！');
} else {
    // 用户不存在
    $response = array('success' => false, 'message' => '该邮箱地址未注册，请确认后再试！');
}

// 返回响应给前端
echo json_encode($response);

// 关闭数据库连接
$conn->close();
?>
