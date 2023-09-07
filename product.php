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

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


if (isset($_POST['isAdd']) && $_POST['isAdd'] == 'true') {

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
    $total_weight = $_POST['total_weight'];
    $total_queue = $_POST['total_queue'];
    $payment_amount_chinese_thai_delivery = $_POST['payment_amount_chinese_thai_delivery'];
    $product_type = $_POST['product_type'];
    $current_time = date('YmdHis'); // ปัจจุบันในรูปแบบ YYYYMMDDHHMMSS
    $status_recorder = $_POST['status_recorder'];

    include 'config_image_path.php'; // This specifies the upload directory

    if (isset($_FILES["image"]) && is_uploaded_file($_FILES["image"]["tmp_name"]) && $_FILES["image"]["error"] === 0) {
        $avatar_name = $_FILES["image"]["name"];
        $avatar_tmp_name = $_FILES["image"]["tmp_name"];

        $random_name = rand(1000, 1000000) . "-" . $current_time . '_' . $avatar_name;
        $upload_name = $upload_dir . strtolower($random_name);
        $upload_name = preg_replace('/\s+/', '-', $upload_name);
        $image =  $random_name;

        if (move_uploaded_file($avatar_tmp_name, $upload_name)) {

            $stmt = $conn->prepare("INSERT INTO product (
                customer_code, tech_china, warehouse_code, cabinet_number, chinese_warehouse, 
                close_cabinet, to_thailand, parcel_status, quantity, wide_size, long_size, height_size, cue_per_piece, 
                weight,total_weight, total_queue, payment_amount_chinese_thai_delivery, product_type, image, 
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
                $wide_size, // เพิ่ม wide_size ตรงนี้
                $long_size,
                $height_size,
                $cue_per_piece,
                $weight,
                $total_weight,
                $total_queue,
                $payment_amount_chinese_thai_delivery,
                $product_type,
                $image,
                $status_recorder
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

                echo json_encode(['message' => 'product added successfully!', 'product_data' => $data_array]);
            } else {
                echo json_encode(['error' => 'Error adding product.']);
            }
        } else {
            echo json_encode(['error' => 'Error adding image.']);
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO product (
            customer_code, tech_china, warehouse_code, cabinet_number, chinese_warehouse, 
            close_cabinet, to_thailand, parcel_status, quantity,  wide_size,long_size,height_size, cue_per_piece, 
            weight,total_weight, total_queue, payment_amount_chinese_thai_delivery, product_type, 
            status_recorder) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $stmt->bind_param(
            "sssssssssssssssssss",
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
            $total_weight,
            $total_queue,
            $payment_amount_chinese_thai_delivery,
            $product_type,
            $status_recorder
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

            echo json_encode(['message' => 'product added successfully!', 'product_data' => $data_array]);
        } else {
            echo json_encode(['error' => 'Error adding product.']);
        }
    }



    // If no duplicates found, insert the new user

} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();
