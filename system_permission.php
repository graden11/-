<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:GET');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');




//连接数据库


require_once("db_connection.php");



//修改权限

if (isset($_GET['id']) && isset($_GET['permission'])) {
    $id = $_GET['id'];
    $authority = $_GET['permission']; // 正确获取权限值

    // 使用预处理语句

    $stmt = $conn->prepare("UPDATE user SET authority = ? WHERE id = ?");
    $stmt->bind_param("si", $authority, $id); // 绑定参数

    if ($stmt->execute()) {
        echo json_encode(['message' => '设置成功！']);
    } else {
        echo json_encode(['error' => '设置失败: ' . $conn->error]);
    }
    $stmt->close(); // 关闭语句
} else {
    echo json_encode(['error' => 'No product ID or authority provided']);
}



// 关闭数据库连接
$conn->close();
?>

