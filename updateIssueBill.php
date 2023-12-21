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
    $id = $_POST['id']; // assuming you're passing the ID for the product you want to update


    $stmt = $conn->prepare("INSERT INTO product (
        customer_code, tech_china, warehouse_code, cabinet_number, chinese_warehouse, 
        close_cabinet, to_thailand, parcel_status, quantity,  wide_size,long_size,height_size, cue_per_piece, 
        weight,inputFields,total_weight, total_queue, payment_amount_chinese_thai_delivery, product_type, 
        status_recorder) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $stmt->bind_param(
        "ssssssssssssssssssss",
        $customer_code,
        $tech_china,
        $warehouse_code,
        $cabinet_number,
        $chinese_warehouse,
        $close_cabinet,
        $to_thailand,
        $parcel_status,
        $quantity,
        $wide_size,
        $long_size,
        $height_size,
        $cue_per_piece,
        $weight,
        json_encode($inputFields), 
        $total_weight,
        $total_queue,
        $payment_amount_chinese_thai_delivery,
        $product_type,
        $status_recorder
    );

    $stmt = $conn->prepare("UPDATE product SET  billing_status = ? WHERE id = ?");
    $stmt->bind_param("ss", $billing_status,  $id);


    if ($stmt->execute()) {

        echo json_encode(['message' => 'price_per_user update successfully!']);
    } else {
        echo json_encode(['error' => 'Error update price_per_user']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();