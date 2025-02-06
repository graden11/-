<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');
// 连接到数据库



require_once("db_connection.php");


//添加商品销售记录
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['class'])) {
        $name = $_POST['class'];



        // 插入采购信息
        $sql = "INSERT INTO category (name) VALUES ('$name')";


        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => '添加成功']);
        } else {
            echo json_encode(['error' => 'Error adding product']);
        }
    }
}


//删除销售记录
elseif($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // 检查是否传递了商品ID
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // 删除商品
        $sql = "DELETE FROM category WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => '删除成功！']);
        } else {
            echo json_encode(['error' => '失败原因: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'No product ID provided']);
    }
}
// 获取数据
elseif($_SERVER['REQUEST_METHOD'] === 'fetch'){
    $sql = "SELECT id, name FROM category ";
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

// 查询数据库获取类别列表
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT name FROM category";
    $result = $conn->query($sql);

    $data = array(); // 创建一个空数组用于存储选项

    // 将选项添加到数组中
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row['name'];
        }
    }

    // 将数组转换为 JSON 格式并输出
    echo json_encode($data);
}





$conn->close();
?>




