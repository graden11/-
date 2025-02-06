<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: x-requested-with, content-type");
header("Content-Type: application/json");

// 连接到数据库



require_once("db_connection.php");

$tag = $_GET['tag']; // 从 URL 参数中获取标签

// 查询数据库获取产品价格
$stmt = $conn->prepare("SELECT price FROM product WHERE tag = ?");
$stmt->bind_param("s", $tag);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $price = $row['price'];
    echo json_encode($price);
} else {
    // 商品不存在，返回错误信息
    echo json_encode("error");
}

$conn->close();
?>
