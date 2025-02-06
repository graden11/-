<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');




//连接数据库


require_once("db_connection.php");


//获取权限级别

    $username=$_POST['username'];
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
        echo json_encode(array('error' => '用户未找到！'));
    }



// 关闭数据库连接
$conn->close();
?>

