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
    $id_type = $_POST['id_type'];
    $kg = $_POST['kg'];
    $cbm = $_POST['cbm'];



    // If no duplicates found, insert the new user
    $stmt = $conn->prepare("INSERT INTO price_per_user (id_user,id_type,kg,cbm) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $id_user, $id_type, $kg, $cbm);


    if ($stmt->execute()) {


        echo json_encode(['message' => 'product type added successfully!']);
    } else {
        echo json_encode(['error' => 'Error adding product type.']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();
