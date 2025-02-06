<?php
// 设置响应头，允许跨域请求
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// 连接数据库
require_once("db_connection.php");

// 检查请求方法是否为POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 获取前端发送的数据


    // 假设获取了用户名
    $username = $_POST["username"];

    // 删除表中对应用户
    $sql = "DELETE FROM user WHERE username = '$username'";
    $result=$conn->query($sql);
    if ($conn->affected_rows > 0) {
        // 用户删除成功，返回成功消息
        $response = "success";
    } else {
        // 用户删除失败，返回错误消息
        $response = "Error deleting user: " . $conn->error;
    }

    // 返回响应给前端
    echo json_encode($response);
} else {
    // 如果请求方法不是POST，返回错误消息
    echo json_encode("请求方法不支持");
}

// 关闭数据库连接
$conn->close();
?>
