<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: x-requested-with,content-type');
header('Content-Type: application/json');

// 连接到数据库




require_once("db_connection.php");

// 添加商品销售记录
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        $sex = $_POST['sex'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $firm = $_POST['firm'];
        $telephone = (string)$telephone; // 将电话号码转换为字符串类型
        // 插入采购信息
        $sql = "INSERT INTO client (name, sex, telephone, email, firm) VALUES ('$name', '$sex', '$telephone', '$email', '$firm')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => '添加成功！']);
        } else {
            echo json_encode(['error' => '失败原因: ' . $conn->error]);
        }
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
    if ($searchMethod === 'name') {
        // 根据用户名进行查找
        $sql = "SELECT * FROM client WHERE name LIKE '%$searchKeyword%'";
    } else if ($searchMethod === 'firm') {
        // 根据权限等级进行查找
        $sql = "SELECT * FROM client WHERE firm = '$searchKeyword'";
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
// 删除客户信息
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // 检查是否传递了客户ID
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];


        $sql = "DELETE FROM client WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => '删除成功！']);
        } else {
            echo json_encode(['error' => '删除失败原因: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'No client ID provided']);
    }
}

// 获取数据
else {
    $sql = "SELECT id, name, sex, telephone, email, firm FROM client";
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
