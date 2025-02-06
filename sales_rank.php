<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: x-requested-with,content-type');
header('Content-Type: application/json');

// 连接数据库
require_once("db_connection.php");

// 查询每个商品的总销售量
$sql = "SELECT tag, SUM(quantity) AS total_sales
        FROM sales
        GROUP BY tag
        ORDER BY total_sales DESC";
$result = $conn->query($sql);

// 初始化结果数组
$ranking = array();

// 处理查询结果
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // 将每个商品及其对应的总销售量添加到结果数组中
        $ranking[] = array(
            'tag' => $row['tag'],
            'total_sales' => $row['total_sales']
        );
    }
}

// 将销售量排行榜返回给前端
echo json_encode($ranking);

// 关闭数据库连接
$conn->close();
?>
