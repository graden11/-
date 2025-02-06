<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: x-requested-with,content-type');
header('Content-Type: application/json');

// 连接到数据库



require_once("db_connection.php");



// 查询库存成本
$sql_inventory_total = "SELECT SUM(quantity * price) AS total_inventory_cost FROM product";
$result = $conn->query($sql_inventory_total);

// 检查查询结果
if ($result) {
    // 将查询结果转换为关联数组
    $row = $result->fetch_assoc();

    // 提取总库存成本并转换为整数类型
    $total_inventory_cost = intval($row['total_inventory_cost']);

    // 返回总库存成本给前端
    echo json_encode(array($total_inventory_cost));
} else {
    // 查询出错时返回错误消息
    echo json_encode(array('error' => 'Failed to fetch inventory cost'));
}


$conn->close();
?>
