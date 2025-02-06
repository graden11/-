<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: x-requested-with,content-type');
header('Content-Type: application/json');

// 连接数据库
require_once("db_connection.php");

// 初始化季度数组
$quarters = array();

// 查询销售数据按季度分类
$sql_sales = "SELECT QUARTER(dates) AS quarter, SUM(quantity*price) AS total_sales 
              FROM sales 
              GROUP BY QUARTER(dates)";
$result_sales = $conn->query($sql_sales);
if ($result_sales->num_rows > 0) {
    while ($row_sales = $result_sales->fetch_assoc()) {
        $quarters[$row_sales['quarter']]['total_sales'] = $row_sales['total_sales'];
    }
}

// 查询成本数据按季度分类
$sql_costs = "SELECT QUARTER(dates) AS quarter, SUM(quantity*price) AS total_costs 
              FROM purchase 
              GROUP BY QUARTER(dates)";
$result_costs = $conn->query($sql_costs);
if ($result_costs->num_rows > 0) {
    while ($row_costs = $result_costs->fetch_assoc()) {
        $quarters[$row_costs['quarter']]['total_costs'] = $row_costs['total_costs'];
    }
}

// 计算总利润
foreach ($quarters as $quarter => $data) {
    $quarters[$quarter]['total_profit'] = isset($data['total_sales']) && isset($data['total_costs']) ? $data['total_sales'] - $data['total_costs'] : 0;
}

// 将按季度分类的数据返回给前端
echo json_encode($quarters);

// 关闭数据库连接
$conn->close();

