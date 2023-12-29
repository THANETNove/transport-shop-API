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

if (isset($_GET['isAdd']) && $_GET['isAdd'] == 'true') {
    $id = $_GET['id'];
    $select_sql = "SELECT
    users.id,users.money FROM users
    WHERE id=?";
    $stmt = $conn->prepare($select_sql);
    $stmt->bind_param("i", $id); // Assuming 'id' is an integer in your database
    $stmt->execute();
    $result = $stmt->get_result();
    $data_array = [];
    while ($row = $result->fetch_assoc()) {
        $data_array[] = $row;
    }

    echo json_encode(['message' => 'product type get successfully!', 'data' => $data_array]);
} else {
    echo json_encode(['error' => 'Welcome Master UNG']);
}

$conn->close();