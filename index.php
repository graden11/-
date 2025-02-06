<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');




//连接数据库


require_once("db_connection.php");


// 更新密码
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $newPassword = $_POST['newPassword'];
    $username=$_POST['username'];
    $password=$_POST['password'];
    $sql1="SELECT * FROM user WHERE password = '".$password."'";
    $result=$conn->query($sql1);
        if($result->num_rows>0){
        $sql = "UPDATE user SET password = '" . $newPassword . "' WHERE username = '" . $username . "'";
        if ($conn->query($sql) === TRUE) {
            // 密码修改成功
            $response = '密码修改成功！';
        } else {
            // 密码修改失败
            $response = '密码修改失败，原因: ' . $conn->error;
        }

    }
    else{
        $response = '旧密码错误！';
    }
// 返回响应 给前端
    echo $response;
}

//获取权限级别
elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
    $username=$_GET['username'];
// 准备 SQL 查询语句
    $sql = "SELECT authority FROM user WHERE username = '$username'";
// 执行查询
    $result = $conn->query($sql);

// 检查是否有查询结果
    if ($result->num_rows >0 ) {
        // 读取查询结果中的权限字段
        $row = $result->fetch_assoc();
        $permission = $row['authority'];

        // 返回权限字段给前端
        echo json_encode($permission);
    } else {
        // 如果没有查询到结果，则返回错误信息
        echo json_encode(array('error' => 'User not found'));
    }

}

// 关闭数据库连接
$conn->close();
?>

