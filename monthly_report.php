<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');

// 连接数据库
require_once("db_connection.php");



// 初始化月份数组
$months = array();

// 查询销售数据按月分类
$sql_sales = "SELECT MONTH(dates) AS month, SUM(quantity*price) AS total_sales 
              FROM sales 
              GROUP BY MONTH(dates)";
$result_sales = $conn->query($sql_sales);
if ($result_sales->num_rows > 0) {
    while ($row_sales = $result_sales->fetch_assoc()) {
        $months[$row_sales['month']]['total_sales'] = $row_sales['total_sales'];
    }
}

// 查询成本数据按月分类
$sql_costs = "SELECT MONTH(dates) AS month, SUM(quantity*price) AS total_costs 
              FROM purchase 
              GROUP BY MONTH(dates)";
$result_costs = $conn->query($sql_costs);
if ($result_costs->num_rows > 0) {
    while ($row_costs = $result_costs->fetch_assoc()) {
        $months[$row_costs['month']]['total_costs'] = $row_costs['total_costs'];
    }
}

//库存成本
//$sql_inventory_total = "SELECT SUM(quantity * price) AS total_inventory_cost FROM product";


// 计算总利润
foreach ($months as $month => $data) {
    $months[$month]['total_profit'] = isset($data['total_sales']) && isset($data['total_costs']) ? $data['total_sales'] - $data['total_costs'] : 0;
}

// 将按月分类的数据返回给前端
echo json_encode($months);

// 关闭数据库连接
$conn->close();
?>
