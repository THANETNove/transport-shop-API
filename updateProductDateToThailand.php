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
    $to_thailand = $_POST['to_thailand'];

    $stmt = $conn->prepare("UPDATE product SET  to_thailand = ? WHERE id = ?");
    $stmt->bind_param("ss", $to_thailand, $id);


    if ($stmt->execute()) {

        echo json_encode(['message' => 'product date to_thailand updated successfully!']);
    } else {
        echo json_encode(['error' => 'Error updating product date. ']);
    }
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}


$conn->close();