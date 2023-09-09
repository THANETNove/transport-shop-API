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
    $kg = $_POST['kg'];
    $cbm = $_POST['cbm'];

    $stmt = $conn->prepare("UPDATE price_per_user SET  kg = ?, cbm = ? WHERE id = ?");
    $stmt->bind_param("sss", $kg, $cbm, $id);


    if ($stmt->execute()) {

        echo json_encode(['message' => 'price_per_user update successfully!']);
    } else {
        echo json_encode(['error' => 'Error update price_per_user']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();
