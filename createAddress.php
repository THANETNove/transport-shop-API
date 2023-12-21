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
    $id_user = $_POST['id_user'];
    $username = $_POST['name'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $subdistricts = $_POST['subdistricts'];
    $districts = $_POST['districts'];
    $provinces = $_POST['provinces'];
    $zip_code = $_POST['zip_code'];

    // If no duplicates found, insert the new user
    $stmt = $conn->prepare("INSERT INTO address (id_user, username, tel, address, subdistricts,districts ,provinces,zip_code) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssss", $id_user, $username, $tel, $address, $subdistricts,$districts ,$provinces,$zip_code);


    if ($stmt->execute()) {

        echo json_encode(['message' => 'product type added successfully!']);
    } else {
        echo json_encode(['error' => 'Error adding product type.']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();