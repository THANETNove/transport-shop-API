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

if (isset($_POST['isAdd']) && $_POST['isAdd'] == 'true') {

    $customer_code = $_POST['customer_code'];
    $tech_china = $_POST['tech_china'];
    $warehouse_code = $_POST['warehouse_code'];
    $cabinet_number = $_POST['cabinet_number'];

    $chinese_warehouse_date = DateTime::createFromFormat('d-m-Y', $_POST['chinese_warehouse']);
    $chinese_warehouse = $chinese_warehouse_date ? $chinese_warehouse_date->format('d-m-Y') : null;
    $close_cabinet_date = DateTime::createFromFormat('d-m-Y', $_POST['close_cabinet']);
    $close_cabinet = $close_cabinet_date ? $close_cabinet_date->format('d-m-Y') : null;
    $to_thailand_date = DateTime::createFromFormat('d-m-Y', $_POST['to_thailand']);
    $to_thailand = $to_thailand_date ? $to_thailand_date->format('d-m-Y') : null;

    $parcel_status = $_POST['parcel_status'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
    $cue_per_piece = $_POST['cue_per_piece'];
    $weight = $_POST['weight'];
    $total_queue = $_POST['total_queue'];
    $payment_amount_chinese_thai_delivery = $_POST['payment_amount_chinese_thai_delivery'];
    $product_type = $_POST['product_type'];
    $current_time = date('YmdHis'); // ปัจจุบันในรูปแบบ YYYYMMDDHHMMSS
    $image_extension = pathinfo($_FILES['image']['name']);
    $image = $current_time . '.' . $image_extension;
    $status_recorder = $_POST['status_recorder'];


    // If no duplicates found, insert the new user
    $stmt = $conn->prepare("INSERT INTO product (
        customer_code, tech_china, warehouse_code, cabinet_number, chinese_warehouse, 
        close_cabinet, to_thailand, parcel_status, quantity, size, cue_per_piece, 
        weight, total_queue, payment_amount_chinese_thai_delivery, product_type,image, 
        status_recorder) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $stmt->bind_param(
        "sssssssssssssssss",
        $customer_code,
        $tech_china,
        $warehouse_code,
        $cabinet_number,
        $chinese_warehouse,
        $close_cabinet,
        $to_thailand,
        $parcel_status,
        $quantity,
        $size,
        $cue_per_piece,
        $weight,
        $total_queue,
        $payment_amount_chinese_thai_delivery,
        $product_type,
        $image,
        $status_recorder
    );


    if ($stmt->execute()) {
        $select_sql = "SELECT * FROM product";
        $stmt = $conn->prepare($select_sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data_array = [];
        while ($row = $result->fetch_assoc()) {
            $data_array[] = $row;
        }

        echo json_encode(['message' => 'product added successfully!', 'product_data' => $data_array]);
    } else {
        echo json_encode(['error' => 'Error adding product.']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();
