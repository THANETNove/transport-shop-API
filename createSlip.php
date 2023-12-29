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

    $id = $_POST['id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $money = $_POST['money'];
    

    include 'config_image_path_slip.php'; // This specifies the upload directory

    if (isset($_FILES["image"]) && is_uploaded_file($_FILES["image"]["tmp_name"]) && $_FILES["image"]["error"] === 0) {
        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION); // นำนามสกุลไฟล์
        $new_avatar_name = uniqid() . "_" . date("Ymd_His") . "." . $file_extension;
        
        $avatar_tmp_name = $_FILES["image"]["tmp_name"];

        $random_name = rand(1000, 1000000) . "-" . $new_avatar_name;
        $upload_name = $upload_slip . strtolower($random_name);
        $upload_name = preg_replace('/\s+/', '-', $upload_name);
        $image =  $random_name;
        
        if (move_uploaded_file($avatar_tmp_name, $upload_name)) {
            $stmt = $conn->prepare("INSERT INTO slip (
                code_user, date, time, money, image) VALUES (?,?,?,?,?)");

            $stmt->bind_param(
                "sssss",
                $id,
                $date,
                $time,
                $money,
                $image,
            );



            if ($stmt->execute()) {
                echo json_encode(['message' => 'slip added successfully!']);
            } else {
                echo json_encode(['error' => 'Error adding product.']);
            }
        } else {
            echo json_encode(['error' => 'Error adding image.']);
        }
    
    }else{
        echo json_encode(['error' => 'ไม่ มีไฟล์ รูปภาพ']);
    }



    // If no duplicates found, insert the new user

} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();