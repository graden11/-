<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');// 获取前端发送的用户名和密码
//$username = $_POST['username'];
//$password = $_POST['password'];



//连接数据库


require_once("db_connection.php");

// 获取通过AJAX传递的用户名和密码
$username = $_POST['username'];
$password = $_POST['password'];
// 执行数据库查询
$sql = "SELECT * FROM user WHERE username = '". $username."' AND password = '".$password."'";
$result = $conn->query($sql);

if ($result->num_rows> 0) {
    // 验证通过，返回成功响应

//    $_SESSION['username'] = $username;// 这里可以根据需要存储更多信息
    setcookie("loggedIn", $username, time() + (86400 * 30), "/"); // 这里设置cookie过期时间为30天

    $response = 'success';
} else {
    // 用户名或密码不正确，返回失败响应
//    echo json_encode('用户名或密码不正确');
    $response='false';
}
echo json_encode($response);

// 关闭数据库连接
$conn->close();
// 检查是否存在名为 "loggedIn" 的cookie


?>