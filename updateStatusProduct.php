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
include 'config_image_path.php'; // This specifies the upload directory

if (isset($_POST['isAdd']) && $_POST['isAdd'] == 'true') {
    $idProduct = $_POST['idProduct'];
    $parcel_status = $_POST['parcel_status'];

    // อัปเดตข้อมูลในฐานข้อมูลเมื่อไม่มีการเปลี่ยนแปลงภาพ
    $stmt = $conn->prepare("UPDATE product SET
        parcel_status=? WHERE id=?");

    $stmt->bind_param(
        "ss",
        $parcel_status,
        $idProduct
    );

    if ($stmt->execute()) {
        $select_sql = "SELECT * FROM product ORDER BY id DESC";
        $stmt = $conn->prepare($select_sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data_array = [];
        while ($row = $result->fetch_assoc()) {
            $data_array[] = $row;
        }
        echo json_encode(['message' => 'product status updated successfully!', 'product_data' => $data_array]);
    } else {
        echo json_encode(['error' => 'Error updating product status.']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}


$conn->close();