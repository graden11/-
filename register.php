<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: x-requested-with,content-type");
header("Content-Type: application/json");

// 连接数据库
require_once("db_connection.php");

// 获取通过 AJAX 传递的用户名、密码和邮箱
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

// 查询是否存在相同的邮箱或用户名
$sql1 = "SELECT * FROM user WHERE email='$email'";
$sql2 = "SELECT * FROM user WHERE username='$username'";
$result1 = $conn->query($sql1);
$result2 = $conn->query($sql2);

if ($result1->num_rows > 0) {
    echo json_encode('emailrepeat');
} elseif ($result2->num_rows > 0) {
    echo json_encode('userrepeat');
} else {
    // 执行数据库插入操作
    $sql = "INSERT INTO user (username, password, email) VALUES ('$username', '$password', '$email')";
    $result = $conn->query($sql);

    if ($result === true) {
        // 插入成功
        $response = 'success';
    } else {
        // 插入失败
        $response = 'false';
    }

    // 返回响应给前端
    echo json_encode($response);
}

// 关闭数据库连接
$conn->close();
?>
