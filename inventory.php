<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');

// 连接到数据库


require_once("db_connection.php");

// 处理 POST 请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 添加商品
    if (isset($_POST['category'])) {
        $category = $_POST['category'];
        $tag = $_POST['tag'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $threshold = $_POST['threshold'];

        $sql = "INSERT INTO product (category, tag, quantity, price,threshold) VALUES ('$category', '$tag', $quantity, $price,$threshold)";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => '添加成功']);
        } else {
            echo json_encode(['error' => '失败原因: ' . $conn->error]);
        }
    }
}
    // 编辑商品


    // 删除商品
    elseif($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // 检查是否传递了商品ID
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // 删除商品
            $sql = "DELETE FROM product WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(['message' => '删除成功！']);
            } else {
                echo json_encode(['error' => '失败原因: ' . $conn->error]);
            }
        } else {
            echo json_encode(['error' => 'No product ID provided']);
        }
    }
    // 查找商品
    elseif($_SERVER['REQUEST_METHOD'] === 'SEEK') {
    // 检查是否传递了标签参数
        // 获取前端发送的查询方法和关键字参数
        $searchMethod = $_GET['method'];
        $searchKeyword = $_GET['keyword'];

// 初始化查询结果数组
        $searchResults = array();

// 根据查询方法执行不同的查询操作
        if ($searchMethod === 'tag') {
            // 根据商品名称进行查找
            $sql = "SELECT * FROM product WHERE tag LIKE '%$searchKeyword%'";
        } else if ($searchMethod === 'id') {
            // 根据商品编号进行查找
            $sql = "SELECT * FROM product WHERE category = '$searchKeyword'";
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





$conn->close();
?>