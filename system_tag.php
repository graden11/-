<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: x-requested-with,content-type');
header('Content-Type: application/json');

// 连接到数据库



require_once("db_connection.php");


// 添加商品销售记录
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tag'])) {
        $tag = $_POST['tag'];
        $category = $_POST['category'];
        $pd = date('Y-m-d', strtotime($_POST['pd'])); // 生产日期
        $exp = date('Y-m-d', strtotime($_POST['exp'])); // 保质期
        $gte = $_POST['gte'];//所属公司

//        $telephone = (string)$telephone; // 将电话号码转换为字符串类型
        // 插入采购信息
        $sql = "INSERT INTO commodity (category,tag, pd, exp, gte ) VALUES ('$category', '$tag', '$pd', '$exp', '$gte')";

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
    if ($searchMethod === 'tag') {
        // 根据用户名进行查找
        $sql = "SELECT * FROM commodity WHERE tag LIKE '%$searchKeyword%'";
    } else if ($searchMethod === 'category') {
        // 根据权限等级进行查找
        $sql = "SELECT * FROM commodity WHERE category = '$searchKeyword'";
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
// 删除销售记录
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // 检查是否传递了商品ID
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];

        // 删除商品
        $sql = "DELETE FROM commodity WHERE id=$id";
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
else {
    $sql = "SELECT id, category, tag, pd, exp, gte FROM commodity";
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
