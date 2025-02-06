<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:GET');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');
// 连接到数据库


require_once("db_connection.php");




// 获取数据
$sql = "SELECT id, category, tag, quantity, price, threshold FROM product";
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
$conn->close();
?>




