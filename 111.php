<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');
// 连接到数据库



require_once("db_connection.php");

//添加商品销售记录
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['category'])) {
        $supplier = $_POST['supplier'];
        $buyer = $_POST['buyer'];
        $category = $_POST['category'];
        $tag = $_POST['tag'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $dates = $_POST['Dates'];
        $number= 0;

        // 插入采购信息
        $sql = "INSERT INTO purchase (supplier, buyer, category, tag, quantity, price, dates) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiis", $supplier, $buyer, $category, $tag, $quantity, $price, $dates);
        $stmt->execute();


        // 检查库存表中是否存在该商品
        $check_sql = "SELECT COUNT(*) AS count FROM product WHERE tag = '$tag'";
        $check_result = $conn->query($check_sql);
        $count = $check_result->fetch_assoc()['count'];

        if ($count == 0) {
            // 如果库存表中不存在该商品，则插入一条新记录
            $insert_sql = "INSERT INTO product (category, tag, quantity, price, threshold) VALUES (?, ?, ?, ?, ?)";
            $stmt1 = $conn->prepare($insert_sql);
            $stmt1->bind_param("ssidi", $category, $tag, $quantity, $price, $number);
            $stmt1->execute();
            if ($stmt->affected_rows > 0 && $stmt1->affected_rows > 0  ) {
                echo json_encode(['message' => 'Product added successfully']);
            } else {
                echo json_encode(['message' => 'Error adding product']);
            }

        } else {
            // 如果库存表中存在该商品，则更新现有记录的数量
            $sql1 = "UPDATE product SET quantity = quantity + ? WHERE tag = ?";
            $stmt2 = $conn->prepare($sql1);
            $stmt2->bind_param("is", $quantity, $tag);
            $stmt2->execute();
            if ($stmt->affected_rows > 0 && $stmt2->affected_rows > 0 ) {
                echo json_encode(['message' => 'Product added successfully']);
            } else {
                echo json_encode(['message' => 'Error adding product']);
            }
        }


        // 更新产品数量


//        if ($stmt->affected_rows > 0 &&($stmt1->affected_rows > 0 || $stmt2->affected_rows > 0) ) {
//            echo json_encode(['message' => 'Product added successfully']);
//        } else {
//            echo json_encode(['error' => 'Error adding product']);
//        }
    }
}

//查找采购记录
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
        $sql = "SELECT * FROM purchase WHERE tag LIKE '%$searchKeyword%'";
    } else if ($searchMethod === 'supplier') {
        // 根据权限等级进行查找
        $sql = "SELECT * FROM purchase WHERE supplier = '$searchKeyword'";
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
//删除销售记录
elseif($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // 检查是否传递了商品ID
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // 删除商品
        $sql = "DELETE FROM purchase WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Product deleted successfully']);
        } else {
            echo json_encode(['error' => 'Error deleting product: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'No product ID provided']);
    }
}
// 获取数据
elseif($_SERVER['REQUEST_METHOD'] === 'fetch'){
    $sql = "SELECT id, supplier, buyer, category, tag, quantity, price, dates FROM purchase";
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




