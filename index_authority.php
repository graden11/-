<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');




//连接数据库


require_once("db_connection.php");

// 检查库存并返回警告信息
function checkInventory() {
    global $conn;


    $sql = "SELECT username FROM user WHERE authority IS NULL ";
    $result = $conn->query($sql);

    $products = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row['username'];
        }
    }
    return $products;
}
// 返回 JSON 格式的响应
$response = array(
    'alert' => false,
    'products' => array()
);

$low_inventory_products = checkInventory();
if (!empty($low_inventory_products)) {
    $response['alert'] = true;
    $response['products'] = $low_inventory_products;
}


echo json_encode($response);



// 关闭数据库连接
$conn->close();
?>

