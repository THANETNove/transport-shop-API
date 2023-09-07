<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['isAdd']) && $_GET['isAdd'] == 'true') {

    /*  $select_sql = "SELECT * FROM users WHERE status = 0 ORDER BY customerCode ASC"; */
    $select_sql = "
   SELECT users.customerCode, price_per_user.*, product_type.name 
   FROM users 
   JOIN price_per_user ON users.id = price_per_user.id_user 
   JOIN product_type ON price_per_user.id_type = product_type.id 
   WHERE users.status = 0 
   ORDER BY users.customerCode ASC
";
    $result = $conn->query($select_sql);

    // ตรวจสอบว่ามีข้อมูลที่ค้นหาเจอหรือไม่
    if ($result->num_rows > 0) {
        $data_array = [];
        while ($row = $result->fetch_assoc()) {
            $data_array[] = $row;
        }
        echo json_encode(['message' => 'CustomerCode get successfully!', 'usersCode_data' => $data_array]);
    } else {
        // ถ้าไม่พบข้อมูล
        echo json_encode(['error_message' => 'No data found']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();
