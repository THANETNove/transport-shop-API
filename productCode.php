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

if ($_POST['isAdd'] && $_POST['isAdd'] == 'true') {
    $idUser = $_POST['id'];
    $code = $_POST['code'];

    // If no duplicates found, insert the new user
    $stmt = $conn->prepare("INSERT INTO product_code (idUser, code) VALUES (?, ?)");
    $stmt->bind_param("ss", $idUser, $code);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'product type added successfully!']);
    } else {
        echo json_encode(['error' => 'Error adding product type.']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();