<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');
// 连接到数据库


require_once("db_connection.php");


if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // 检查是否传递了商品ID
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // 删除商品
        $sql = "DELETE FROM user WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => '删除成功!']);
        } else {
            echo json_encode(['error' => '删除失败原因: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => '未检测到ID']);
    }
}
//查找用户
elseif($_SERVER['REQUEST_METHOD'] === 'SEEK') {
    // 检查是否传递了标签参数
    // 获取前端发送的查询方法和关键字参数
    $searchMethod = $_GET['method'];
    $searchKeyword = $_GET['keyword'];

// 初始化查询结果数组
    $searchResults = array();

// 根据查询方法执行不同的查询操作
    if ($searchMethod === 'username') {
        // 根据用户名进行查找
        $sql = "SELECT * FROM user WHERE username LIKE '%$searchKeyword%'";
    } else if ($searchMethod === 'authority') {
        // 根据权限等级进行查找
        $sql = "SELECT * FROM user WHERE authority = '$searchKeyword'";
    } else {
        // 如果查询方法未知，返回错误消息
        echo json_encode(array('error' => 'Unknown search method'));
        exit(); // 结束脚本执行
    }

// 执行查询操作
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // 将查询结果添加到结果数组中
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
        // 返回查询结果给前端
        echo json_encode($searchResults);
    } else {
        // 如果查询结果为空，返回空数组给前端
        echo json_encode($searchResults);
    }
}
// 获取数据
else{
    $sql = "SELECT id, username, password, email,authority FROM user";
    $result = $conn->query($sql);
// 将结果转换为关联数组并输出为JSON格式
    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode([]); // 返回空数组的 JSON 字符串
    }

}



$conn->close();
?>




