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
        $sql = "SELECT * FROM client WHERE id=$id";
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
    $name = $_POST['name'];
    $sex = $_POST['sex'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $firm=$_POST['firm'];

    $sql = "UPDATE client SET name ='$name', sex='$sex', telephone=$telephone, email='$email', firm='$firm' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => '更新成功！']);
    } else {
        echo json_encode(['error' => '失败原因: ' . $conn->error]);
    }

}


// 关闭数据库连接
$conn->close();
?>

