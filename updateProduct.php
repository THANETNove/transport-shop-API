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


$idProduct = $_POST['idProduct'];
$customer_code = $_POST['customer_code'];
$tech_china = $_POST['tech_china'];
$warehouse_code = $_POST['warehouse_code'];
$cabinet_number = $_POST['cabinet_number'];
$chinese_warehouse =  $_POST['chinese_warehouse'];
$close_cabinet = $_POST['close_cabinet'];
$to_thailand = $_POST['to_thailand'];

$parcel_status = $_POST['parcel_status'];
$quantity = $_POST['quantity'];
$wide_size = $_POST['wide_size'];
$long_size = $_POST['long_size'];
$height_size = $_POST['height_size'];
$cue_per_piece = $_POST['cue_per_piece'];
$weight = $_POST['weight'];
$inputFields = json_decode($_POST['inputFields'], true);
$total_weight = $_POST['total_weight'];
$total_queue = $_POST['total_queue'];
$payment_amount_chinese_thai_delivery = $_POST['payment_amount_chinese_thai_delivery'];
$product_type = $_POST['product_type'];
$current_time = date('YmdHis'); // ปัจจุบันในรูปแบบ YYYYMMDDHHMMSS
$status_recorder = $_POST['status_recorder'];
$old_image = $_POST['old_image'];

if (isset($_FILES["image"]) && is_uploaded_file($_FILES["image"]["tmp_name"]) && $_FILES["image"]["error"] === 0) {
    // เปรียบเทียบค่ารูปภาพเก่าและรูปภาพใหม่ ถ้าไม่เท่ากัน


    // อัปโหลดรูปภาพใหม่
    $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION); // นำนามสกุลไฟล์
    $new_avatar_name = uniqid() . "_" . date("Ymd_His") . "." . $file_extension;
    $avatar_tmp_name = $_FILES["image"]["tmp_name"];

    $random_name = rand(1000, 1000000) . "-" . $new_avatar_name;
    $upload_name = $upload_dir . strtolower($random_name);
    $upload_name = preg_replace('/\s+/', '-', $upload_name);
    $image =  $random_name;

    if (move_uploaded_file($avatar_tmp_name, $upload_name)) {

        $old_image_path = $upload_dir . $old_image;
        if (file_exists($old_image_path)) {
            unlink($old_image_path); // ลบภาพเก่า
        }


        // อัปเดตข้อมูลในฐานข้อมูลเมื่อรูปภาพเปลี่ยนแปลง
        $stmt = $conn->prepare("UPDATE product SET
        customer_code=?, tech_china=?, warehouse_code=?, cabinet_number=?, chinese_warehouse=?, 
        close_cabinet=?, to_thailand=?, parcel_status=?, quantity=?,  wide_size=?,long_size=?,height_size=?, cue_per_piece=?, 
        weight=?,inputFields=?, total_weight=?,total_queue=?, payment_amount_chinese_thai_delivery=?, product_type=?,image=?, 
        status_recorder=? WHERE id=?");

        $stmt->bind_param(
            "ssssssssssssssssssssss",
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
            $image,
            $status_recorder,
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
            echo json_encode(['message' => 'product updated successfully!', 'product_data' => $data_array]);
        } else {
            echo json_encode(['error' => 'Error updating product.']);
        }
    } else {
        echo json_encode(['error' => 'Error uploading image.']);
    }
} else {
    // อัปเดตข้อมูลในฐานข้อมูลเมื่อไม่มีการเปลี่ยนแปลงภาพ
    $stmt = $conn->prepare("UPDATE product SET
        customer_code=?, tech_china=?, warehouse_code=?, cabinet_number=?, chinese_warehouse=?, 
        close_cabinet=?, to_thailand=?, parcel_status=?, quantity=?, wide_size=?,long_size=?,height_size=?, cue_per_piece=?, 
        weight=?,inputFields=?,total_weight=?, total_queue=?, payment_amount_chinese_thai_delivery=?, product_type=?, 
        status_recorder=? WHERE id=?");

    $stmt->bind_param(
        "sssssssssssssssssssss",
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
        $status_recorder,
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
        echo json_encode(['message' => 'product updated successfully!', 'product_data' => $data_array]);
    } else {
        echo json_encode(['error' => 'Error updating product.']);
    }
}


$conn->close();