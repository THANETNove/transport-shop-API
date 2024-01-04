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
 
    $uploaded_file = $_FILES["image"]["tmp_name"];
    $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION); // นำนามสกุลไฟล์
    $new_avatar_name = uniqid() . "_" . date("Ymd_His") . "." . $file_extension;


    $avatar_tmp_name = $_FILES["image"]["tmp_name"];
    

    $random_name = rand(1000, 1000000) . "-" .$new_avatar_name;
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

     if ($status == "ถูกยกเลิก") {
        // Fetch total_amount and id_user from bill table
        $stmt_b = $conn->prepare("SELECT total_amount, id_user FROM bill WHERE id = ?");
        $stmt_b->bind_param("s", $id);
        $stmt_b->execute();
        $stmt_b->bind_result($totalAmount, $userId);
        $stmt_b->fetch();
        $stmt_b->close();

        if ($userId) {
            // Fetch money from users table based on id_user
            $stmt_u = $conn->prepare("SELECT money FROM users WHERE id = ?");
            $stmt_u->bind_param("s", $userId);
            $stmt_u->execute();
            $stmt_u->bind_result($money);
            $stmt_u->fetch();
            $stmt_u->close();

            // Calculate the sum of total_amount and money
            $price = $totalAmount + $money;

            $stmt3 = $conn->prepare("UPDATE users SET money = ? WHERE id = ?");
            $stmt3->bind_param("ii", $price, $userId);
            $stmt3->execute();
            $stmt3->close();

            // Now $price contains the sum of total_amount and money
            /* echo "Total Price: $price"; */
        } else {
            echo "Bill not found";
        }

        
       /*  $stmt3 = $conn->prepare("UPDATE users SET money = ? WHERE id = ?");
        $stmt3->bind_param("ii", $point, $id);
        $stmt3->execute();
        $stmt3->close(); */
     }

    if ($stmt->execute()) {

        echo json_encode(['message' => 'Bill Status updated successfully!  2']);
    } else {
        echo json_encode(['error' => 'Error updating product.']);
    }
}


$conn->close();