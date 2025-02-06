<?php
header( "Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Content-Type: application/json');

//连接数据库
require_once("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // 查询匹配标签的商品
        $sql = "SELECT * FROM sales WHERE id=$id";
        $result = $conn->query($sql);

        $data = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'No product found']);
        }
    } else {
        echo json_encode(['error' => 'No product ID provided']);
    }
}

else{
    $id = $_POST['id'];
    $salesperson = $_POST['salesperson'];
    $client = $_POST['client'];
    $category = $_POST['category'];
    $tag = $_POST['tag'];
    $quantity=$_POST['quantity'];
    $price = $_POST['price'];
    $dates = $_POST['dates'];

    $sql = "UPDATE sales SET salesperson ='$salesperson', client='$client', category='$category', tag='$tag', quantity=$quantity, price=$price, dates='$dates' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => '更新成功！']);
    } else {
        echo json_encode(['error' => '失败原因: ' . $conn->error]);
    }

}


// 关闭数据库连接
$conn->close();
?>

