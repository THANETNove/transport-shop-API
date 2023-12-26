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


$id = $_POST['id'];
$status = $_POST['status'];

if (isset($_FILES["image"]) && is_uploaded_file($_FILES["image"]["tmp_name"]) && $_FILES["image"]["error"] === 0) {
    // เปรียบเทียบค่ารูปภาพเก่าและรูปภาพใหม่ ถ้าไม่เท่ากัน


    // อัปโหลดรูปภาพใหม่
    $avatar_name = $_FILES["image"]["name"];
    $avatar_tmp_name = $_FILES["image"]["tmp_name"];

    $random_name = rand(1000, 1000000) . "-" . $current_time . '_' . $avatar_name;
    $upload_name = $upload_dir . strtolower($random_name);
    $upload_name = preg_replace('/\s+/', '-', $upload_name);
    $image =  $random_name;

    if (move_uploaded_file($avatar_tmp_name, $upload_name)) {

        // อัปเดตข้อมูลในฐานข้อมูลเมื่อรูปภาพเปลี่ยนแปลง
        $stmt = $conn->prepare("UPDATE bill SET
        status=? ,image=? WHERE id=?");

        $stmt->bind_param(
            "sss",
            $status,
            $image,    
            $id,    
        );

        if ($stmt->execute()) {

            echo json_encode(['message' => 'Bill Status updated successfully! 1']);
        } else {
            echo json_encode(['error' => 'Error updating product.']);
        }
    } else {
        echo json_encode(['error' => 'Error uploading image.']);
    }
} else {
     // อัปเดตข้อมูลในฐานข้อมูลเมื่อรูปภาพเปลี่ยนแปลง
     $stmt = $conn->prepare("UPDATE bill SET
     status=?  WHERE id=?");

     $stmt->bind_param(
         "ss",
         $status, 
         $id,    
     );

    if ($stmt->execute()) {

        echo json_encode(['message' => 'Bill Status updated successfully!  2']);
    } else {
        echo json_encode(['error' => 'Error updating product.']);
    }
}


$conn->close();