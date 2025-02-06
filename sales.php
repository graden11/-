<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');
// 连接到数据库



require_once("db_connection.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['category'])) {
        $client = $_POST['client'];
        $salesperson = $_POST['salesperson'];
        $category = $_POST['category'];
        $tag = $_POST['tag'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $dates = $_POST['Dates'];

        // 插入销售信息
        $sql = "INSERT INTO sales (client, salesperson,category, tag, quantity, price, dates) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiis", $client, $salesperson, $category, $tag, $quantity, $price, $dates);
        $stmt->execute();

        // 更新产品数量
        $sql1 = "UPDATE product SET quantity = quantity - ? WHERE tag = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("is", $quantity, $tag);
        $stmt1->execute();

        if ($stmt->affected_rows > 0 && $stmt1->affected_rows > 0) {
            echo json_encode(['message' => '添加成功！']);
        } else {
            echo json_encode(['error' => '添加失败！']);
        }
    }
}

//删除销售记录
elseif($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // 检查是否传递了商品ID
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // 删除商品
        $sql = "DELETE FROM sales WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => '删除成功！']);
        } else {
            echo json_encode(['error' => '失败原因: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'No product ID provided']);
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
    if ($searchMethod === 'tag') {
        // 根据用户名进行查找
        $sql = "SELECT * FROM sales WHERE tag LIKE '%$searchKeyword%'";
    } else if ($searchMethod === 'client') {
        // 根据客户进行查找
        $sql = "SELECT * FROM sales WHERE client = '$searchKeyword'";
    } else if ($searchMethod === 'month') {
        // 根据月份进行查找
        $sql = "SELECT * FROM sales WHERE DATE_FORMAT(dates, '%Y-%m') = '$searchKeyword'";
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
elseif($_SERVER['REQUEST_METHOD'] === 'fetch'){
    $sql = "SELECT id, client, salesperson, category, tag, quantity, price, dates FROM sales";
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




