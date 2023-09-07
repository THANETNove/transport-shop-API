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
    $id = $_GET['id'];
    $select_sql = "SELECT * FROM users WHERE customerCode LIKE ? AND status = 0";
    $stmt = $conn->prepare($select_sql);
    $search_term = "%$id%"; // เพิ่ม % เพื่อให้เป็นการค้นหา fuzzy match
    $stmt->bind_param("s", $search_term); // ใช้ "s" เนื่องจาก customerCode เป็นสตริง
    $stmt->execute();
    $result = $stmt->get_result();
    // ตรวจสอบว่ามีข้อมูลที่ค้นหาเจอหรือไม่
    if ($result->num_rows > 0) {
        $data_array = [];
        while ($row = $result->fetch_assoc()) {
            $data_array[] = $row;
        }
        echo json_encode(['message' => 'CustomerCode get successfully!', 'userCode_data' => $data_array]);
    } else {
        // ถ้าไม่พบข้อมูล
        echo json_encode(['error_message' => 'No data found']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();
